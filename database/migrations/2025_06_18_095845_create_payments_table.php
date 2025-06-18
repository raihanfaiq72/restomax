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
        Schema::create('payments', function (Blueprint $table) {
            $table->string('id')->primary(); 
            $table->string('order_id');
            $table->string('payment_method'); 
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->string('status');
            $table->timestamp('payment_time');
            $table->timestamps();

            // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
