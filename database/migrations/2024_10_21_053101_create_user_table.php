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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->String("email", 60)->unique();
            $table->text("password", 32);
            $table->unsignedBigInteger('rolId');
            $table->unsignedBigInteger('employeeId');
            $table->foreign('rolId')->references('id')->on('rol');
            $table->foreign('employeeId')->references('id')->on('employee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
