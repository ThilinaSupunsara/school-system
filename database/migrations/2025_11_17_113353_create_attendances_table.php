<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('attendance_date');

            // 'present', 'absent', 'late', 'half_day'
            $table->string('status', 50)->default('absent');

            $table->text('remarks')->nullable(); // e.g., "Sick leave"
            $table->timestamps();

            // එක ශිෂ්‍යයෙකුට එක දවසකට එක attendance record එකයි
            $table->unique(['student_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
