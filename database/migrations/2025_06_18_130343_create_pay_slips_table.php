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
        Schema::create('pay_slips', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->string('payroll_run_id', 36);
            $table->string('user_id', 36);
            $table->decimal('base_salary', 10, 2)->nullable();
            $table->decimal('hourly_wage', 10, 2)->nullable();
            $table->decimal('hours_worked', 8, 2)->nullable();
            $table->decimal('overtime_hours', 8, 2)->nullable();
            $table->decimal('overtime_pay', 10, 2)->nullable();
            $table->json('allowances_json')->nullable();
            $table->json('deductions_json')->nullable();
            $table->decimal('net_pay', 10, 2);
            $table->timestamp('slip_generated_at');
            $table->timestamps();

            // Foreign keys
            $table->foreign('payroll_run_id')->references('id')->on('payroll_runs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_slips');
    }
};
