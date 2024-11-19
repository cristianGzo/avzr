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
        //
        Schema::create('salesProjection', function (Blueprint $table) {
            $table->id();
            $table->DateTime("startDate");
            $table->DateTime("endDate");
            $table->integer("value");
            $table->unsignedBigInteger('productionCategoryId');
            $table->foreign('productionCategoryId')->references('id')->on('productionCategory');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};