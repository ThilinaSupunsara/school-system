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
        // 1. payrolls table එකට 'paid_amount' එකතු කිරීම
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0)->after('net_salary');
        });

        // 2. ගෙවීම් ඉතිහාසය සඳහා අලුත් table එකක්
        Schema::create('payroll_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('method')->default('cash'); // cash, bank
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
