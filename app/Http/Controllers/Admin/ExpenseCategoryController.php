<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    // Category List එක සහ Create Form එක පෙන්වීම
    public function index()
    {
        $categories = ExpenseCategory::all();
        return view('admin.expense_categories.index', compact('categories'));
    }

    // අලුත් Category එකක් Save කිරීම
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
        ]);

        ExpenseCategory::create(['name' => $request->name]);

        return back()->with('success', 'Category Added Successfully!');
    }

    // Category එකක් Delete කිරීම
    public function destroy($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Category Deleted!');
    }
}
