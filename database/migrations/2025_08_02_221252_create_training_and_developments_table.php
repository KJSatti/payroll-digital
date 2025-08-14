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
        Schema::create('training_and_developments', function (Blueprint $table) {
            $table->id(); // Training ID
            $table->unsignedBigInteger('employee_id');
            $table->string('training_type');
            $table->date('training_date');
            $table->string('training_duration');
            $table->string('trainer');
            $table->string('status'); // Completed / Ongoing

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
        Schema::dropIfExists('training_and_developments');
    }
};
