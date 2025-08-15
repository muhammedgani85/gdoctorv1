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
        Schema::create('appointment_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id')->unique();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('bp')->nullable();
            $table->string('o2')->nullable();
            $table->string('sugar_pp')->nullable();
            $table->string('sugar_af')->nullable();
            $table->text('notes')->nullable();
            $table->text('consulting_fees')->nullable();
            $table->text('medicine')->nullable();
            $table->text('scan_required')->nullable();
            $table->text('scan_centre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_notes');
    }
};
