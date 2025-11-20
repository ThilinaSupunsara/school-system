<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $roles = UserRole::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:user_roles,name',
        ]);

        // නම සිම්පල් අකුරින් save කරමු (lowercase) - අපේ logic වලට ලේසියි
        UserRole::create([
            'name' => strtolower($request->name),
        ]);

        return back()->with('success', 'New role created successfully.');
    }

    public function destroy(UserRole $role)
    {
        // ප්‍රධාන roles 3 delete කරන්න දෙන්න එපා
        if (in_array($role->name, ['admin', 'accountant', 'teacher'])) {
            return back()->with('error', 'Cannot delete core system roles.');
        }

        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }
}
