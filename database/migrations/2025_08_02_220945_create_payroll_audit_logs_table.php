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
        Schema::create('payroll_audit_logs', function (Blueprint $table) {
            $table->id(); // Log ID
            $table->unsignedBigInteger('employee_id');
            $table->string('action'); // Edit, Delete, Add, etc.
            $table->dateTime('date_and_time');
            $table->unsignedBigInteger('user_id'); // who made the change
            $table->text('description')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_audit_logs');
    }
};
