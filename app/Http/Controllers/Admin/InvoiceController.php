<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\Grade;
use App\Models\Invoice;
use App\Models\Section;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Filters sadaha Data gannawa
        $grades = Grade::all();
        $sections = Section::all();

        // 2. Query eka patan gannawa
        $query = Invoice::with('student.section.grade');

        // --- Filter Logic ---

        // Grade Filter (Student haraha Section haraha Grade eka check karanawa)
        if ($request->filled('grade_id')) {
            $query->whereHas('student.section', function ($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });
        }

        // Section Filter
        if ($request->filled('section_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('section_id', $request->section_id);
            });
        }

        // Search Student (Name or Admission No)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('admission_no', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Due Date Filter
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        // 3. Pagination (10 bagin) saha Appends (Filters රැකගැනීමට)
        $invoices = $query->latest()
                          ->paginate(10)
                          ->appends($request->all());

        return view('admin.invoices.index', compact('invoices', 'grades', 'sections'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        // Invoice එකට අදාළ හැමදේම Eager Load කරමු
        $invoice->load('student.section.grade', 'invoiceItems.feeCategory', 'payments');

        // Invoice එකේ ගෙවන්න ඉතුරු ගාණ حساب කරමු
        $balance = $invoice->total_amount - $invoice->paid_amount;

        return view('admin.invoices.show', compact('invoice', 'balance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('finance.invoices.index')
                         ->with('success', 'Invoice deleted successfully.');
    }

    public function showGenerateForm()
    {
        $grades = Grade::all();
        $feeCategories = FeeCategory::all();
        $sections = Section::all(); // <-- 1. Sections ටිකත් View එකට යවමු

        return view('admin.invoices.generate', compact('grades', 'feeCategories', 'sections'));
    }

    public function processGenerate(Request $request)
    {
        // 1. Validation
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'nullable|exists:sections,id', // Optional: specific class
            'fee_category_id' => 'required|exists:fee_categories,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
        ]);

        // 2. Find Fee Structure (Based on Grade)
        $feeStructure = FeeStructure::where('grade_id', $request->grade_id)
                                    ->where('fee_category_id', $request->fee_category_id)
                                    ->first();

        if (!$feeStructure) {
            return back()->with('error', 'No matching Fee Structure found for this Grade and Category.');
        }

        $baseAmount = $feeStructure->amount;

        // 3. Get Students (With Scholarships Eager Loaded)
        $studentsQuery = Student::with('scholarships'); // Important: Load scholarships to avoid N+1 issue

        if ($request->filled('section_id')) {
            // If a specific section is selected
            $studentsQuery->where('section_id', $request->section_id);
        } else {
            // Otherwise, get all students in that Grade
            $studentsQuery->whereHas('section', function ($query) use ($request) {
                $query->where('grade_id', $request->grade_id);
            });
        }

        $students = $studentsQuery->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No students found to generate invoices.');
        }

        // 4. Start Transaction & Generate
        DB::beginTransaction();
        try {
            $generatedCount = 0;

            foreach ($students as $student) {

                // --- Calculate Discount ---
                // Sum up all active scholarships assigned to this student
                $discountAmount = $student->scholarships->sum('amount');

                // Final Invoice Amount (Cannot be less than 0)
                $finalAmount = max(0, $baseAmount - $discountAmount);

                // --- Create Invoice ---
                $invoice = $student->invoices()->create([
                    'invoice_date' => $request->invoice_date,
                    'due_date' => $request->due_date,
                    'total_amount' => $finalAmount, // The discounted amount
                    'paid_amount' => 0,
                    'status' => 'pending',
                ]);

                // --- Create Invoice Item ---
                // We record the ORIGINAL fee category here.
                // Note: Ideally, you might want to add a separate item for the discount,
                // but for now, just recording the fee category is sufficient.
                $invoice->invoiceItems()->create([
                    'fee_category_id' => $request->fee_category_id,
                    'amount' => $baseAmount, // The original fee amount
                ]);

                $generatedCount++;
            }

            DB::commit();

            return redirect()->route('finance.invoices.index')
                             ->with('success', "$generatedCount invoices generated successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred during generation: ' . $e->getMessage());
        }
    }

    public function storePayment(Request $request, Invoice $invoice)
    {
        // 1. Validation
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . ($invoice->total_amount - $invoice->paid_amount),
            'payment_date' => 'required|date',
            'method' => 'required|string|in:cash,bank_transfer',
        ]);

        // Database Transaction එකක් දාමු
        DB::beginTransaction();
        try {
            // 2. Payment එක හදමු
            $invoice->payments()->create([
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'method' => $request->method,
            ]);

            // 3. Invoice එකේ 'paid_amount' එක update කරමු
            $newPaidAmount = $invoice->paid_amount + $request->amount;

            // 4. Invoice Status එක update කරමු
            $newStatus = 'pending';
            if ($newPaidAmount >= $invoice->total_amount) {
                $newStatus = 'paid';
            } elseif ($newPaidAmount > 0) {
                $newStatus = 'partial';
            }

            $invoice->update([
                'paid_amount' => $newPaidAmount,
                'status' => $newStatus,
            ]);

            // 5. Transaction එක Commit කරමු
            DB::commit();

            return redirect()->route('finance.invoices.show', $invoice->id)
                            ->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while recording payment. Error: ' . $e->getMessage());
        }
    }

    public function print(Invoice $invoice)
    {
        // Invoice Data ගන්නවා
        $invoice->load('student.section.grade', 'invoiceItems.feeCategory', 'payments');

        // Balance එක
        $balance = $invoice->total_amount - $invoice->paid_amount;

        return view('admin.invoices.print', compact('invoice', 'balance'));
    }

    public function markAsSettled(Invoice $invoice)
    {
        // Security Check: ගාණ 0 නම් විතරයි මේක කරන්න පුළුවන්
        if ($invoice->total_amount > 0) {
            return back()->with('error', 'This invoice has a balance. Please record a payment.');
        }

        // Status එක කෙලින්ම 'paid' කරනවා
        $invoice->update([
            'status' => 'paid',
            'paid_amount' => 0, // 0 ම තමයි, ඒත් sure වෙන්න දානවා
        ]);

        return back()->with('success', 'Invoice marked as settled (Full Scholarship).');
    }

    public function exportPdf(Request $request)
    {
        // 1. Query එක පටන් ගන්නවා (Filter Logic එකමයි)
        $query = Invoice::with('student.section.grade');

        // --- Filter Logic (Same as index method) ---
        if ($request->filled('grade_id')) {
            $query->whereHas('student.section', function ($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });
        }
        if ($request->filled('section_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('section_id', $request->section_id);
            });
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('admission_no', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        // 2. Data ගන්නවා (Pagination නෑ, ඔක්කොම ගන්නවා)
        $invoices = $query->latest()->get();

        // 3. Totals ගණනය කිරීම (Summary සඳහා)
        $totalAmount = $invoices->sum('total_amount');
        $totalPaid = $invoices->sum('paid_amount');
        $totalDue = $totalAmount - $totalPaid;

        // 4. PDF Load කිරීම
        $schoolSettings = \App\Models\SchoolSetting::first();

        $pdf = Pdf::loadView('admin.invoices.pdf_list', compact(
            'invoices', 'totalAmount', 'totalPaid', 'totalDue', 'schoolSettings'
        ));

        // 5. Download
        return $pdf->download('Invoices-Report-' . now()->format('Y-m-d') . '.pdf');
    }
}
