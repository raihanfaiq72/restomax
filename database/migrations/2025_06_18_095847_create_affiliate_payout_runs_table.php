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
            $table->string('id')->primary(); // UUID
            $table->date('payout_date');
            $table->decimal('total_amount', 15, 2);
            $table->string('status'); // ENUM: 'Pending', 'Completed'
            $table->string('processed_by_user_id'); // Restomax Admin
            $table->text('notes')->nullable();
            $table->timestamps();

            // $table->foreign('processed_by_user_id')->references('id')->on('users')->onDelete('restrict');
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
