<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relationships
        $feeStructures = FeeStructure::with('grade', 'feeCategory')->get();
        return view('admin.fee_structures.index', compact('feeStructures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Dropdowns දෙකටම data යවන්න
        $grades = Grade::all();
        $feeCategories = FeeCategory::all();
        return view('admin.fee_structures.create', compact('grades', 'feeCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'grade_id' => [
                'required',
                'exists:grades,id',
                // grade_id + fee_category_id combination එක unique වෙන්න ඕන
                Rule::unique('fee_structures')->where(function ($query) use ($request) {
                    return $query->where('fee_category_id', $request->fee_category_id);
                })
            ],
            'fee_category_id' => 'required|exists:fee_categories,id',
            'amount' => 'required|numeric|min:0',
        ]);

        FeeStructure::create($request->all());

        return redirect()->route('finance.fee-structures.index')
                         ->with('success', 'Fee Structure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeStructure $feeStructure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeStructure $feeStructure)
    {
        // Dropdowns දෙකටම data සහ edit කරන structure එක යවන්න
        $grades = Grade::all();
        $feeCategories = FeeCategory::all();
        return view('admin.fee_structures.edit', compact('feeStructure', 'grades', 'feeCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeStructure $feeStructure)
    {
        $request->validate([
            'grade_id' => [
                'required',
                'exists:grades,id',
                // Unique rule එක, මේ ID එක ignore කරලා
                Rule::unique('fee_structures')->where(function ($query) use ($request) {
                    return $query->where('fee_category_id', $request->fee_category_id);
                })->ignore($feeStructure->id)
            ],
            'fee_category_id' => 'required|exists:fee_categories,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $feeStructure->update($request->all());

        return redirect()->route('finance.fee-structures.index')
                         ->with('success', 'Fee Structure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeStructure $feeStructure)
    {
        // Fee Structure එකක් delete කරාට ප්‍රශ්නයක් නෑ (cascade errors නෑ)
        $feeStructure->delete();
        return redirect()->route('finance.fee-structures.index')
                         ->with('success', 'Fee Structure deleted successfully.');
    }
}
