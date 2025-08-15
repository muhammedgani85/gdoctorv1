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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('phone_number');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->integer('age');
            $table->unsignedBigInteger('doctor_id');
            $table->integer('slot_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('token_number');
            $table->date('appointment_date');
            $table->string('status')->default('Pending');
            $table->string('btype')->nullable();
            $table->string('illness')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
