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
      Schema::create('sandhas', function (Blueprint $table) {
        $table->id();
        $table->string('sandha_name');
        $table->integer('duration');
        $table->decimal('price', 10, 2);
        $table->enum('status', ['active', 'inactive']);
        $table->text('description')->nullable();
        $table->unsignedBigInteger('added_by');
        $table->timestamps();
        $table->softDeletes(); // Adds deleted_at column for soft deletes
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sandhas');
    }
};
