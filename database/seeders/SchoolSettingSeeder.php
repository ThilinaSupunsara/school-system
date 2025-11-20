<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       SchoolSetting::create([
        'school_name' => 'My School Name',
        'school_address' => '123 School Road, City',
        'phone' => '011-1234567',
        'email' => 'info@school.com',
    ]);
    }
}
