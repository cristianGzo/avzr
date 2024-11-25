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
        Schema::table('salesProjection', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('weekId')->default(1);
            $table->foreign('weekId')->references('id')->on('weeks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salesProjection', function (Blueprint $table) {
            //
        });
    }
};
