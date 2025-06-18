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
        Schema::create('affiliate_referrals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('affiliate_id');
            $table->string('referred_company_id');
            $table->timestamp('referral_date');
            $table->decimal('commission_percentage_applied', 5, 4);
            $table->decimal('calculated_commission_amount', 15, 2);
            $table->string('payout_status'); 
            $table->string('payout_run_id')->nullable();
            $table->timestamps();

            // $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            // $table->foreign('referred_company_id')->references('id')->on('companies')->onDelete('cascade');
            // $table->foreign('payout_run_id')->references('id')->on('affiliate_payout_runs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_referrals');
    }
};
