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
        Schema::create('medical_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('batch_number')->nullable();
            $table->string('unit');
            $table->integer('quantity');
            $table->integer('min_quantity_threshold')->default(5);
            $table->decimal('price_per_unit', 8, 2);
            $table->date('expiry_date')->nullable();
            $table->string('vendor')->nullable();
            $table->timestamps();

            $table->unique(['item_name', 'unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_stocks');
    }
};
