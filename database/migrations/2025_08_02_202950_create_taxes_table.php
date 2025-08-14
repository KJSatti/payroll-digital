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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id(); // Tax ID
            $table->unsignedBigInteger('employee_id');
            $table->decimal('federal_tax', 10, 2);
            $table->decimal('state_tax', 10, 2);
            $table->decimal('local_tax', 10, 2);
            $table->decimal('social_security_tax', 10, 2);
            $table->decimal('medicare_tax', 10, 2);

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
        Schema::dropIfExists('taxes');
    }
};
