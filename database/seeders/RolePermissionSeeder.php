<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cache Clear කිරීම (වැදගත්)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. සියලුම Permissions ලිස්ට් එක (All Features Included)
        $permissions = [
            // --- Students Management ---
            'student.view',
            'student.create',
            'student.edit',
            'student.delete',

            // --- Staff Management ---
            'staff.view',
            'staff.create',
            'staff.edit',
            'staff.delete',
            'staff.Payroll',

            // --- Finance: Invoices ---
            'invoice.view',
            'invoice.create',
            'invoice.edit',
            'invoice.delete',

            // --- Finance: Payroll (Added Edit) ---
            'payroll.view',
            'payroll.create',
            'payroll.edit',   // <-- New
            'payroll.delete',

            // --- Finance: Expenses (Added Edit) ---
            'expense.view',
            'expense.create',
            'expense.edit',   // <-- New
            'expense.delete',

            // --- Academic (AssignTeacher) ---
            'AssignTeacher.view',


            // --- Finance: Fee Setup (Scholarships) ---
            'Scholarships.view',


            // --- Reports ---
            'report.financial',  // Salary sheets, Outstanding, etc.
            'report.attendance', // Daily attendance, Registers

            // --- Finance: Fee Setup (Structures) ---
            'Structure.view',
            'Structure.create',
            'Structure.edit',
            'Structure.delete',

        ];

        // 3. Permissions Database එකේ නිර්මාණය කිරීම
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 4. Roles නිර්මාණය කිරීම
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAccountant = Role::firstOrCreate(['name' => 'accountant']);
        $roleTeacher = Role::firstOrCreate(['name' => 'teacher']);

        // 5. Admin ට සියලුම බලතල දීම (Super Admin)
        $roleAdmin->givePermissionTo(Permission::all());


    }
}
