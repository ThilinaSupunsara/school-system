<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ----- Admin User -----
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // ----- Accountant User -----
        // 1. User Record එක හදනවා
        $accountantUser = User::create([
            'name' => 'Accountant User',
            'email' => 'accountant@school.com',
            'password' => Hash::make('password'),
            'role' => 'accountant',
        ]);

        // 2. ඒ User ට අදාළ Staff Record එක හදනවා
        Staff::create([
            'user_id' => $accountantUser->id,
            'designation' => 'School Accountant',
            'join_date' => now(), // අද දවස දාමු
            'basic_salary' => 50000.00
        ]);

        // ----- Teacher User -----
        // 1. User Record එක හදනවා
        $teacherUser = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // 2. ඒ User ට අදාළ Staff Record එක හදනවා
        Staff::create([
            'user_id' => $teacherUser->id,
            'designation' => 'Maths Teacher',
            'join_date' => now(), // අද දවස දාමු
            'basic_salary' => 45000.00
        ]);
    }
}
