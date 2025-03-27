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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to users table
            $table->decimal('amount', 10, 2); // Loan amount
            $table->integer('term'); // Number of months for repayment
            $table->decimal('remaining_balance', 10, 2); // Outstanding balance
            $table->enum('status', ['active', 'paid', 'defaulted'])->default('active'); // Loan status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
