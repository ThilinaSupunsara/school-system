<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    // 1. Matrix එක පෙන්වන පිටුව
    public function index()
    {
        $roles = Role::where('name', '!=', 'admin')->get();
        $permissions = Permission::all();

        // Permissions කාණ්ඩ (Groups) වලට වෙන් කිරීම
        // උදා: 'student.view' -> 'Student' group එකට
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name); // තිත් (.) වලින් කඩනවා
            return ucwords(str_replace('-', ' ', $parts[0])); // Group Name (Student, Invoice, etc.)
        });

        return view('admin.roles.matrix', compact('roles', 'groupedPermissions'));
    }

    // 2. Permissions Save කිරීම
    public function update(Request $request)
    {
        // Form එකෙන් එන Data (permissions array) ගන්න
        $data = $request->input('permissions', []);

        // Admin හැර අනිත් roles ටික loop කරන්න
        $roles = Role::where('name', '!=', 'admin')->get();

        foreach ($roles as $role) {
            // Form එකේ මේ Role එකට අදාළව ටික් කරපු Permissions ටික ගන්න
            // ටික් කරලා නැත්නම් හිස් array එකක් [] ගන්න
            $permissionsForRole = $data[$role->id] ?? [];

            // Spatie හරහා Sync කරන්න (ටික් කරපු ටික දෙනවා, අනිත්වා අයින් කරනවා)
            $role->syncPermissions($permissionsForRole);
        }

        return back()->with('success', 'Permissions updated successfully!');
    }
}
