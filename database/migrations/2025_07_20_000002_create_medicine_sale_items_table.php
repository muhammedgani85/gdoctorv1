<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicine_sale_id');
            $table->unsignedBigInteger('stock_item_id');
            $table->string('medicine_name');
            $table->string('sku');
            $table->string('batch_number');
            $table->string('unit');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_sale_items');
    }
};
