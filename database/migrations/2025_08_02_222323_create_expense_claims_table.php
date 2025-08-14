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
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id(); // Expense ID
            $table->unsignedBigInteger('employee_id');
            $table->string('expense_type');
            $table->decimal('amount_claimed', 10, 2);
            $table->date('date_of_expense');
            $table->string('status'); // Approved / Pending / Rejected
            $table->date('approval_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable(); // User ID

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
        Schema::dropIfExists('expense_claims');
    }
};
