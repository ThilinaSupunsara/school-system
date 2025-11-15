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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->unsignedSmallInteger('year'); // e.g., 2025
            $table->unsignedTinyInteger('month'); // e.g., 11 (for November)

            $table->decimal('basic_salary', 10, 2); // Basic salary at the time of processing
            $table->decimal('total_allowances', 10, 2); // Sum of all allowances
            $table->decimal('total_deductions', 10, 2); // Sum of all deductions
            $table->decimal('net_salary', 10, 2); // Basic + Allowances - Deductions

            $table->string('status', 50)->default('generated'); // 'generated', 'paid'
            $table->timestamps();

            // එක staff member කෙනෙකුට එක මාසෙකට එක payroll record එකයි
            $table->unique(['staff_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
