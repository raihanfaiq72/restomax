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
        Schema::create('affiliate_recurring_commissions', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->string('affiliate_id', 36);
            $table->string('referred_company_id', 36);
            $table->string('subscription_id', 36);
            $table->decimal('commission_amount', 10, 2);
            $table->date('billing_period_start');
            $table->date('billing_period_end');
            $table->enum('payout_status', ['Pending', 'Paid'])->default('Pending');
            $table->string('payout_run_id', 36)->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->foreign('referred_company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('subscription_id')->references('id')->on('company_subscriptions')->onDelete('cascade');
            $table->foreign('payout_run_id')->references('id')->on('affiliate_payout_runs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_recurring_commissions');
    }
};
