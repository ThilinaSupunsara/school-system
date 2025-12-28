<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    // 1. List එක පෙන්වීම
    // 1. INDEX METHOD (Filters + Pagination සමග)
    public function index(Request $request)
    {
        if (!auth()->user()->can('expense.view')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }

        $query = Expense::with('category', 'issuer')->latest();

        // --- Filters ---
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Pagination 10
        $expenses = $query->paginate(10)->appends($request->all());

        // Filter Form එකට Categories යවන්න ඕන
        $categories = \App\Models\ExpenseCategory::all();

        return view('admin.expenses.index', compact('expenses', 'categories'));
    }

    // 2. PRINT REPORT METHOD (අලුත් එක)
    public function printReport(Request $request)
    {
        $query = Expense::with('category', 'issuer')->latest();

        // --- Filters (Index එකේ Logic එකමයි) ---
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Print කරනකොට Pagination ඕන නෑ, ඔක්කොම ගන්නවා
        $expenses = $query->get();
        $schoolSettings = \App\Models\SchoolSetting::first();

        return view('admin.expenses.print_report', compact('expenses', 'schoolSettings'));
    }

    // 2. අලුත් වියදමක් පටන් ගන්න Form එක (Issue Cash)
    // 1. CREATE Method Update
    public function create()
    {
        if (!auth()->user()->can('expense.create')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }

        $staffMembers = \App\Models\Staff::with('user')->get();

        // --- Categories ටික ගන්න ---
        $categories = \App\Models\ExpenseCategory::all();

        return view('admin.expenses.create', compact('staffMembers', 'categories'));
    }

    // 2. STORE Method Update
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:expense_categories,id',
            'amount_given' => 'required|numeric|min:0',
            'recipient_type' => 'required|in:staff,external',
            'staff_id' => 'required_if:recipient_type,staff|nullable|exists:staff,id',
            'external_name' => 'required_if:recipient_type,external|nullable|string|max:255',
        ]);

        // Form Data ටික Variable එකකට ගන්නවා
        $data = $request->all();

        // ** අලුත් කොටස: Log වී ඇති User (Accountant) ගේ ID එක එකතු කිරීම **
        $data['issued_by'] = Auth::id();

        Expense::create($data);

        return redirect()->route('finance.expenses.index')
                         ->with('success', 'Cash issued successfully.');
    }

    // 4. පියවීමේ පිටුව (Settle Page)
    public function edit(Expense $expense)
    {
        if (!auth()->user()->can('expense.edit')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }
        // දැනටමත් settle කරලා නම් edit කරන්න බෑ
        if($expense->status == 'completed') {
            return back()->with('error', 'This expense is already settled.');
        }
        return view('admin.expenses.settle', compact('expense'));
    }

    // 5. පියවීම Save කිරීම (Settle & Upload Receipt)
    public function update(Request $request, Expense $expense)
    {
        if (!auth()->user()->can('expense.edit')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }
        $request->validate([
            'amount_spent' => 'required|numeric|min:0',
            'receipt' => 'nullable|image|max:2048', // Optional Image
        ]);

        $data = [
            'amount_spent' => $request->amount_spent,
            'status' => 'completed',
        ];

        // Receipt එකක් upload කරලා නම් විතරක් save කරන්න
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $data['receipt_path'] = $path;
        }

        $expense->update($data);

        return redirect()->route('finance.expenses.index')
                         ->with('success', 'Expense settled successfully.');
    }

    // 6. Delete (අවශ්‍ය නම්)
    public function destroy(Expense $expense)
    {
        if (!auth()->user()->can('expense.delete')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }
        if($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }
        $expense->delete();
        return back()->with('success', 'Record deleted.');
    }

    // රිසිට් එක පෙන්වන පිටුව
    public function showReceipt(Expense $expense)
    {
        // රිසිට් එකක් නැත්නම් ආපහු යවන්න
        if (!$expense->receipt_path) {
            abort(404, 'Receipt not found');
        }

        return view('admin.expenses.view_receipt', compact('expense'));
    }
}
