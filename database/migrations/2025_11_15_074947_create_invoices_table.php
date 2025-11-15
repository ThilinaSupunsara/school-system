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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('invoice_date'); // Invoice එක හැදූ දිනය
            $table->date('due_date'); // ගෙවිය යුතු අවසන් දිනය
            $table->decimal('total_amount', 10, 2); // Invoice එකේ මුළු ගාණ
            $table->decimal('paid_amount', 10, 2)->default(0.00); // ගෙවූ මුදල
            $table->string('status', 50)->default('pending'); // 'pending', 'paid', 'partial'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
