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
        Schema::create('affiliate_payout_runs', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->date('payout_date');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['Pending', 'Completed']);
            $table->string('processed_by_user_id', 36)->nullable(); // Restomax Admin
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('processed_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_payout_runs');
    }
};
