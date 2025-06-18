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
            $table->string('id', 36)->primary(); // UUID
            $table->string('order_id', 36);
            $table->enum('payment_method', ['Cash', 'Card', 'QRIS', 'Gopay', 'OVO', 'Dana', 'ShopeePay', 'LinkAja', 'Transfer Bank']);
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['Success', 'Failed', 'Pending']);
            $table->timestamp('payment_time');
            $table->timestamps();

            // Foreign key
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
