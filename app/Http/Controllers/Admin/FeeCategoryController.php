<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FeeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feeCategories = FeeCategory::all();
        return view('admin.fee_categories.index', compact('feeCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fee_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:fee_categories',
        ]);

        FeeCategory::create($request->all());

        return redirect()->route('finance.fee-categories.index') // <-- Route name එක 'finance.'
                         ->with('success', 'Fee Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeCategory $feeCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeCategory $feeCategory)
    {
        return view('admin.fee_categories.edit', compact('feeCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeCategory $feeCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:fee_categories,name,' . $feeCategory->id,
        ]);

        $feeCategory->update($request->all());

        return redirect()->route('finance.fee-categories.index') // <-- Route name එක 'finance.'
                         ->with('success', 'Fee Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeCategory $feeCategory)
    {
        try {
            $feeCategory->delete();
            return redirect()->route('finance.fee-categories.index')
                             ->with('success', 'Fee Category deleted successfully.');
        } catch (QueryException $e) {
            // Foreign key error (FeeStructure එකක පාවිච්චි කරලා නම්)
            return redirect()->route('finance.fee-categories.index')
                             ->with('error', 'Cannot delete this category. It is already in use by a Fee Structure.');
        }
    }
}
