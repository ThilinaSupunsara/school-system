<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::with('grade')->get();

        return view('admin.sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grades = Grade::all();

        return view('admin.sections.create', compact('grades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id', // 'grades' table එකේ තියෙන id එකක්ද කියලා බලනවා
        ]);

        Section::create([
            'name' => $request->name,
            'grade_id' => $request->grade_id,
        ]);

        return redirect()->route('admin.sections.index')
                         ->with('success', 'Section created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        $grades = Grade::all();

        return view('admin.sections.edit', compact('section', 'grades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
        ]);

        // 2. Update
        $section->update([
            'name' => $request->name,
            'grade_id' => $request->grade_id,
        ]);

        // 3. Redirect Back
        return redirect()->route('admin.sections.index')
                         ->with('success', 'Section updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        // 2. destroy method එක මෙහෙම වෙනස් කරන්න
        try {

            // Delete කරන්න උත්සාහ කරනවා
            $section->delete();

            // සාර්ථක වුණොත්
            return redirect()->route('admin.sections.index')
                             ->with('success', 'Section deleted successfully.');

        } catch (QueryException $e) {

            
            return redirect()->route('admin.sections.index')
                             ->with('error', 'Cannot delete this section. Students are still assigned to it.');
        }

    }
}
