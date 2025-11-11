<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staffMembers = Staff::with('user')->get();

        return view('admin.staff.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // User Details
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:accountant,teacher'],

            // Staff Details
            'designation' => ['required', 'string', 'max:255'],
            'join_date' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
        ]);

        // 2. User ව හදමු
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 3. Staff member ව හදමු (අලුත් user ID එක දාලා)
        $user->staff()->create([
            'designation' => $request->designation,
            'join_date' => $request->join_date,
            'phone' => $request->phone,
            'basic_salary' => $request->basic_salary,
        ]);

        return redirect()->route('admin.staff.index')
                         ->with('success', 'Staff member registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        // අදාළ user ව හොයාගමු
        $user = $staff->user;

        // 1. Validation
        $request->validate([
            // User Details
            'name' => ['required', 'string', 'max:255'],
            // 'unique' rule එකේදී, මේ user ගේ ID එක ignore කරන්න ඕන
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            // Password එක nullable (හිස් වෙන්න පුළුවන්)
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:accountant,teacher'],

            // Staff Details
            'designation' => ['required', 'string', 'max:255'],
            'join_date' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
        ]);

        // 2. User Model එක Update කිරීම
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // 3. Password එක Update කිරීම (password field එක හිස් නැත්නම් විතරක්)
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // 4. Staff Model එක Update කිරීම
        $staff->update([
            'designation' => $request->designation,
            'join_date' => $request->join_date,
            'phone' => $request->phone,
            'basic_salary' => $request->basic_salary,
        ]);

        // 5. Redirect Back
        return redirect()->route('admin.staff.index')
                         ->with('success', 'Staff member updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        // 2. destroy method එක මෙහෙම වෙනස් කරන්න
        try {

            // Staff record එකට අදාළ User record එක හොයාගන්නවා
            $user = $staff->user;

            // User record එක delete කරනවා.
            // onDelete('cascade') නිසා Staff record එකත් delete වෙනවා.
            $user->delete();

            // සාර්ථක වුණොත්
            return redirect()->route('admin.staff.index')
                             ->with('success', 'Staff member deleted successfully.');

        } catch (QueryException $e) {

            // Database Error එකක් ආවොත්
            return redirect()->route('admin.staff.index')
                             ->with('error', 'Cannot delete this staff member. They may have related records.');
        }

    }
}
