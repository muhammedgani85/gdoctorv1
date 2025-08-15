<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id');
            $table->string('medicine');
            $table->string('morning')->nullable();
            $table->string('afternoon')->nullable();
            $table->string('evening')->nullable();
            $table->integer('days')->nullable();
            $table->timestamps();
            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('prescription_items');
    }
};
