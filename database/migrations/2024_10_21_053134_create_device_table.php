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
        Schema::create('device', function (Blueprint $table) {
            $table->id();
            $table->String("noSerial", 60);
            $table->text("description", 200)->nullable();
            $table->integer("stock");
            $table->String("estado", 50);
            $table->String("qr",250)->nullable();
            $table->unsignedBigInteger('modelId');
            $table->unsignedBigInteger('categoryId');
            $table->foreign('modelId')->references('id')->on('model');
            $table->foreign('categoryId')->references('id')->on('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device');
    }
};
