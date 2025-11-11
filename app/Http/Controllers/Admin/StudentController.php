<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('section.grade')->get();

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::with('grade')->get();

        return view('admin.students.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'admission_no' => 'required|string|max:100|unique:students',
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'section_id' => 'required|exists:sections,id',
        ]);

        Student::create([
            'admission_no' => $request->admission_no,
            'name' => $request->name,
            'dob' => $request->dob,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'section_id' => $request->section_id,
        ]);

        return redirect()->route('admin.students.index')
                         ->with('success', 'Student registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $sections = Section::with('grade')->get();

        return view('admin.students.edit', compact('student', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        // 1. Validation
        $request->validate([
            // 'unique' rule එකේදී, මේ student ගේ ID එක ignore කරන්න ඕන
            'admission_no' => 'required|string|max:100|unique:students,admission_no,' . $student->id,
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'section_id' => 'required|exists:sections,id',
        ]);

        // 2. Update
        $student->update([
            'admission_no' => $request->admission_no,
            'name' => $request->name,
            'dob' => $request->dob,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'section_id' => $request->section_id,
        ]);

        // 3. Redirect Back
        return redirect()->route('admin.students.index')
                         ->with('success', 'Student details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // 2. destroy method එක මෙහෙම වෙනස් කරන්න
        try {

            // Delete කරන්න උත්සාහ කරනවා
            $student->delete();

            // සාර්ථක වුණොත්
            return redirect()->route('admin.students.index')
                             ->with('success', 'Student deleted successfully.');

        } catch (QueryException $e) {

            // Database Error එකක් ආවොත්
            // අනාගතයේදී fees records වගේ දේවල් නිසා වෙන්න පුළුවන්
            return redirect()->route('admin.students.index')
                             ->with('error', 'Cannot delete this student. They may have related records (like fee payments).');
        }
    }
}
