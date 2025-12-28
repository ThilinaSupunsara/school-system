<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\Student;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::all();
        return view('admin.scholarships.index', compact('scholarships'));
    }

    // 2. අලුත් Scholarship Type එකක් save කිරීම
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        Scholarship::create($request->all());
        return back()->with('success', 'Scholarship type created.');
    }

    // 3. ශිෂ්‍යයෙකුට Scholarship එකක් Assign කරන පිටුව
    public function assignForm(Student $student)
    {
        if (!auth()->user()->can('Scholarships.view')) {
            abort(403, 'SORRY! You do not have permission to this.');
        }
        $scholarships = Scholarship::all();
        // ළමයාට දැනට තියෙන scholarships ටික load කරනවා
        $student->load('scholarships');
        return view('admin.scholarships.assign', compact('student', 'scholarships'));
    }
    public function destroy(Scholarship $scholarship)
    {
        // ශිෂ්‍යත්ව වර්ගය මකා දමයි.
        // Pivot table (student_scholarship) එකේ දත්තත් 'onDelete cascade' නිසා මැකෙයි.
        $scholarship->delete();

        return back()->with('success', 'Scholarship type deleted successfully.');
    }

    // 4. Assign කිරීම save කරන function එක
    public function assignStore(Request $request, Student $student)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
        ]);

        // attach() මගින් සම්බන්ධය හදනවා
        $student->scholarships()->attach($request->scholarship_id);

        return back()->with('success', 'Scholarship assigned to student.');
    }

    // 5. Assign කරපු එකක් අයින් කිරීම (Remove)
    public function assignDestroy(Student $student, Scholarship $scholarship)
    {
        // detach() මගින් සම්බන්ධය කඩනවා
        $student->scholarships()->detach($scholarship->id);

        return back()->with('success', 'Scholarship removed.');
    }
}
