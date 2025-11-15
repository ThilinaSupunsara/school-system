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
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
            $table->foreignId('fee_category_id')->constrained('fee_categories')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // e.g., 7000.00
            $table->timestamps();

            // එකම Grade එකට එකම Category එක දෙපාරක් දාන්න බෑ
            $table->unique(['grade_id', 'fee_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
