<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\Grade;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('student.section.grade')
                             ->latest()
                             ->paginate(20); // පිටු (Pagination) දාමු

        return view('admin.invoices.index', compact('invoices'));
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
        // Form එකේ dropdowns වලට data
        $grades = Grade::all();
        $feeCategories = FeeCategory::all();

        return view('admin.invoices.generate', compact('grades', 'feeCategories'));
    }

    public function processGenerate(Request $request)
    {
        // 1. Validation
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'fee_category_id' => 'required|exists:fee_categories,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
        ]);

        // 2. අදාළ Fee Structure එක සහ ගාණ හොයාගන්නවා
        $feeStructure = FeeStructure::where('grade_id', $request->grade_id)
                                    ->where('fee_category_id', $request->fee_category_id)
                                    ->first();

        if (!$feeStructure) {
            return back()->with('error', 'No matching Fee Structure found for this Grade and Category.');
        }

        $amount = $feeStructure->amount;

        // 3. අදාළ Grade එකේ (සහ ඊට අදාළ sections වල) ශිෂ්‍යයින්ව හොයාගන්නවා
        $students = Student::whereHas('section', function ($query) use ($request) {
            $query->where('grade_id', $request->grade_id);
        })->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No students found in this grade.');
        }

        // 4. Database Transaction එකක් පටන් ගන්නවා
        // (Invoice 100ක් හදද්දී 50දි fail වුණොත්, 50ම rollback කරන්න)
        DB::beginTransaction();
        try {

            $generatedCount = 0;
            foreach ($students as $student) {

                // 5. අලුත් Invoice එකක් හදනවා
                $invoice = $student->invoices()->create([
                    'invoice_date' => $request->invoice_date,
                    'due_date' => $request->due_date,
                    'total_amount' => $amount,
                    'paid_amount' => 0,
                    'status' => 'pending',
                ]);

                // 6. ඊට අදාළ Invoice Item එක හදනවා
                $invoice->invoiceItems()->create([
                    'fee_category_id' => $request->fee_category_id,
                    'amount' => $amount,
                ]);

                $generatedCount++;
            }

            // 7. Transaction එක Commit කරනවා (සාර්ථකයි)
            DB::commit();

            return redirect()->route('finance.invoices.index') // Invoice list එකට යවමු
                            ->with('success', "$generatedCount invoices generated successfully for " . $students->first()->section->grade->name . ".");

        } catch (\Exception $e) {
            // 8. Transaction එක Rollback කරනවා (අසාර්ථකයි)
            DB::rollBack();
            return back()->with('error', 'An error occurred during invoice generation. No invoices were created. Error: ' . $e->getMessage());
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
}
