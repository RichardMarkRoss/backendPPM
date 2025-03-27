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
        Schema::create('received_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade'); // Link to loans
            $table->decimal('amount', 10, 2); // Amount paid
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending'); // Status
            $table->timestamp('paid_at')->nullable(); // Timestamp of repayment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('received_repayments');
    }
};
