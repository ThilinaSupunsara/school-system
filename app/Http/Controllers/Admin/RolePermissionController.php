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
        // Admin හැර අනිත් roles ටික ගන්න (Admin ගේ බලතල වෙනස් කරන්න දෙන්න හොඳ නෑ)
        $roles = Role::where('name', '!=', 'admin')->get();

        // Permissions ඔක්කොම ගන්න
        $permissions = Permission::all();

        return view('admin.roles.matrix', compact('roles', 'permissions'));
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
