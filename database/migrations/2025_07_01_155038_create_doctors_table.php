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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_name');
            $table->string('phone_number');
            $table->string('speciality');
            $table->string('availability_days'); // e.g., "Monday,Tuesday"
            $table->enum('status', ['Active', 'InActive'])->default('Active');
            $table->integer('fees')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
