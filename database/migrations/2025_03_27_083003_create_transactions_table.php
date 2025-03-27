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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debit_card_id')->constrained()->onDelete('cascade'); // Link to debit cards
            $table->decimal('amount', 10, 2); // Transaction amount
            $table->enum('type', ['deposit', 'withdrawal', 'purchase']); // Transaction type
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending'); // Status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
