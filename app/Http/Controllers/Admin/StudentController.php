<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('student.view')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }
        // 1. Filters walata awashya Data gannawa
        $grades = Grade::all();
        $sections = Section::all();

        // 2. Query eka patan gannawa (With Eager Loading)
        $query = Student::with('section.grade');

        // --- Filter Logic ---

        // Grade eka thorala nam
        if ($request->filled('grade_id')) {
            $query->whereHas('section', function($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });
        }

        // Section eka thorala nam
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        // Search keyword (Name ho Admission No)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('admission_no', 'like', "%{$search}%");
            });
        }

        // 3. Data gannawa (Pagination ekka)
        // paginate(10) = Page ekaka 10 denai
        // appends() = Page maru weddi filters nathi wenne nathuwa thiyagannawa
        $students = $query->orderBy('id', 'desc')
                          ->paginate(10)
                          ->appends($request->all());

        return view('admin.students.index', compact('students', 'grades', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('student.create')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }
        $sections = Section::with('grade')->get();

        return view('admin.students.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('student.create')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }
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

        return redirect()->route('finance.students.index')
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
        if (!auth()->user()->can('student.edit')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }
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
        return redirect()->route('finance.students.index')
                         ->with('success', 'Student details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if (!auth()->user()->can('student.delete')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }
        // 2. destroy method එක මෙහෙම වෙනස් කරන්න
        try {

            // Delete කරන්න උත්සාහ කරනවා
            $student->delete();

            // සාර්ථක වුණොත්
            return redirect()->route('finance.students.index')
                             ->with('success', 'Student deleted successfully.');

        } catch (QueryException $e) {

            // Database Error එකක් ආවොත්
            // අනාගතයේදී fees records වගේ දේවල් නිසා වෙන්න පුළුවන්
            return redirect()->route('finance.students.index')
                             ->with('error', 'Cannot delete this student. They may have related records (like fee payments).');
        }
    }
}
