<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $grades = Grade::all();


        return view('admin.grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.grades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:grades',
        ]);


        Grade::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.grades.index')
                         ->with('success', 'Grade created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        return view('admin.grades.edit', compact('grade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        // 1. Validation
        // 'unique' rule එකේදී, මේ grade එකේ ID එක ignore කරන්න ඕන
        $request->validate([
            'name' => 'required|string|max:255|unique:grades,name,' . $grade->id,
        ]);

        // 2. Update (දත්ත update කිරීම)
        $grade->update([
            'name' => $request->name,
        ]);

        // 3. Redirect Back (ආපසු යැවීම)
        return redirect()->route('admin.grades.index')
                         ->with('success', 'Grade updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        try {
        // 1. Model එක Delete කිරීම
        $grade->delete();

        // 2. Redirect Back (ආපසු යැවීම)
        return redirect()->route('admin.grades.index')
                         ->with('success', 'Grade deleted successfully.');

        } catch (QueryException $e) {


            return redirect()->route('admin.grades.index')
                             ->with('error', 'Cannot delete this Grade. Students are still assigned to it.');
        }
    }
}
