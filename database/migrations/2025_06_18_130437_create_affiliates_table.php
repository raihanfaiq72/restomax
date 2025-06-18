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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->string('user_id', 36)->nullable(); // If affiliate is also a Restomax user
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('referral_code')->unique();
            $table->string('unique_link_token')->unique();
            $table->decimal('commission_rate', 5, 2); // e.g., 0.15 for 15%
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key (nullable)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
