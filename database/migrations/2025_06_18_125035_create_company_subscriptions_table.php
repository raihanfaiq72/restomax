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
        Schema::create('company_subscriptions', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->string('company_id', 36)->nullable(); // Akan diisi foreign key setelah companies dibuat
            $table->string('subscription_plan_id', 36);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price_locked_monthly', 10, 2)->nullable();
            $table->json('features_locked')->nullable();
            $table->enum('status', ['Active', 'Expired', 'Cancelled', 'Pending_Payment']);
            $table->timestamp('last_billed_at')->nullable();
            $table->timestamp('next_billing_at')->nullable();
            $table->timestamps();

            // Foreign key ke subscription_plans
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_subscriptions');
    }
};
