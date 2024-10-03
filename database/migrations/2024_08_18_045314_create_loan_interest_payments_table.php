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
        Schema::create('loan_interest_payments', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('loan_id'); // Foreign key to loans table
          $table->unsignedBigInteger('loan_interest_id'); // Foreign key to loan_interest table
          $table->decimal('payment_amount', 15, 2); // Amount paid
          $table->date('payment_date'); // Date of payment
          $table->string('payment_method'); // Method of payment (e.g., cash, bank transfer)
          $table->timestamps();
          $table->softDeletes();

          // Define the foreign key constraints
          $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_interest_payments');
    }
};
