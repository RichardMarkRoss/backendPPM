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
        Schema::create('scheduled_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade'); // Link to loans
            $table->decimal('amount_due', 10, 2); // Amount scheduled to be paid
            $table->date('due_date'); // Due date for repayment
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending'); // Repayment status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_repayments');
    }
};
