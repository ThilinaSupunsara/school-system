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
        Schema::create('expenses', function (Blueprint $table) {
           $table->id();
            $table->string('description'); // වැඩේ මොකක්ද? (Paint buying)

            // --- 1. කවුද ගෙනියන්නේ? ---
            $table->string('recipient_type'); // 'staff' or 'external'
            $table->foreignId('staff_id')->nullable()->constrained('staff')->onDelete('set null'); // Staff නම් ID එක
            $table->string('external_name')->nullable(); // පිටස්තර නම් නම

            // --- 2. මුදල් විස්තර ---
            $table->decimal('amount_given', 10, 2); // අතට දුන් මුදල
            $table->decimal('amount_spent', 10, 2)->nullable(); // ඇත්තටම ගිය වියදම (Settle කරද්දි දාන්නේ)

            // --- 3. Receipt & Status ---
            $table->string('receipt_path')->nullable(); // රිසිට් එක (Optional)
            $table->string('status')->default('pending'); // 'pending', 'completed'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
