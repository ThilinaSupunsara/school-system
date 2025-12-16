<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Permission List එක (ඔබට අවශ්‍ය ඕනෑම එකක් මෙතනට දාන්න)
        $permissions = [
            // Invoices
            'invoice.view',
            'invoice.create',
            'invoice.edit',
            'invoice.delete', // <-- Critical

            // Payroll
            'payroll.view',
            'payroll.create',
            'payroll.delete', // <-- Critical

            // Students
            'student.view',
            'student.create',
            'student.edit',
            'student.delete', // <-- Critical

            // Expenses
            'expense.view',
            'expense.create',
            'expense.delete', // <-- Critical
        ];

        // 2. Permissions Database එකට Save කිරීම
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Roles හදමු
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $accountantRole = Role::firstOrCreate(['name' => 'accountant']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);

        // 4. Admin ට ඔක්කොම බලතල දෙනවා
        $adminRole->givePermissionTo(Permission::all());

        // 5. Accountant ට "Delete" හැර අනිත්වා දෙනවා (Default Setup)
        // උදාහරණයක් ලෙස: Accountant ට Invoice Delete කරන්න බෑ, ඒත් හදන්න පුළුවන්
        $accountantRole->givePermissionTo([
            'invoice.view', 'invoice.create', 'invoice.edit',
            'payroll.view', 'payroll.create',
            'student.view',
            'expense.view', 'expense.create'
        ]);

        // (පසුව Admin Panel එකෙන් අපිට මේවා වෙනස් කරන්න පුළුවන්)

    }
}
