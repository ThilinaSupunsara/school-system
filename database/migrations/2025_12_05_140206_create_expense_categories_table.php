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
        Schema::create('expense_categories', function (Blueprint $table) {
           $table->id();
            $table->string('name')->unique(); // Category Name (e.g. Transport)
            $table->timestamps();
        });
        // 2. Expenses Table එකට 'category_id' එකතු කරන්න
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('description')
                ->constrained('expense_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_categories');
    }
};
