<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Staff;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Filter Dropdowns සඳහා Data
        $grades = Grade::all();
        $allSections = Section::all(); // මෙය Dropdown එක පිරවීමට පමණයි

        // 2. Query එක පටන් ගන්නවා
        $query = Section::with('grade', 'classTeacher.user');

        // --- Filter Logic ---

        // Grade Filter
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        // Section Dropdown Filter (කලින් තිබ්බ Search Text එක වෙනුවට)
        if ($request->filled('section_id')) {
            // Ambiguous error එන එක නවත්වන්න table නම එක්කම දාමු
            $query->where('sections.id', $request->section_id);
        }

        // 3. Data ගන්නවා (Pagination 10)
        $sections = $query->join('grades', 'sections.grade_id', '=', 'grades.id')
                          ->orderBy('grades.name')
                          ->orderBy('sections.name')
                          ->select('sections.*')
                          ->paginate(10)
                          ->appends($request->all());

        return view('admin.sections.index', compact('sections', 'grades', 'allSections'));
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

    public function showAssignTeacherForm(Section $section)
    {
        // Section එකත් එක්කම, දැනට ඉන්න teacher වත් load කරගන්නවා
        $section->load('classTeacher.user');

        // Teacher dropdown එකට data
        $teachers = Staff::whereHas('user', function ($query) {
            $query->where('role', 'teacher');
        })->get();

        // අලුත් view එකකට යවනවා
        return view('admin.sections.assign_teacher', compact('section', 'teachers'));
    }

    /**
     * Store the assigned class teacher.
     */
    public function storeAssignTeacher(Request $request, Section $section)
    {
        $request->validate([
            'class_teacher_id' => 'required|exists:staff,id',
        ]);

        $section->update([
            'class_teacher_id' => $request->class_teacher_id,
        ]);

        return redirect()->route('admin.sections.assign_teacher.form', $section->id)
                         ->with('success', 'Class Teacher assigned successfully.');
    }

    /**
     * Remove the assigned class teacher.
     */
    public function removeAssignTeacher(Section $section)
    {
        // Teacher ව null කරනවා
        $section->update([
            'class_teacher_id' => null,
        ]);

        return redirect()->route('admin.sections.assign_teacher.form', $section->id)
                         ->with('success', 'Class Teacher removed successfully.');
    }
}
