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
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // Employee ID
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->date('date_of_birth');
            $table->date('hire_date');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('position_id');
            $table->string('status'); // e.g. active, terminated
        
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
