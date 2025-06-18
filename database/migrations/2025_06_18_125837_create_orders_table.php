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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->string('company_id', 36);
            $table->string('outlet_id', 36);
            $table->string('customer_id', 36)->nullable();
            $table->string('user_id', 36)->nullable(); // Cashier/Waiter
            $table->string('table_id', 36)->nullable();
            $table->enum('order_type', ['Dine-in', 'Takeaway', 'Delivery', 'Self-order', 'Marketplace', 'Catering']);
            $table->enum('status', ['Pending', 'Confirmed', 'Preparing', 'Ready', 'Completed', 'Cancelled', 'Refunded']);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('service_charge_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['Pending', 'Paid', 'Partially_Paid', 'Refunded']);
            $table->text('notes')->nullable();
            $table->timestamp('order_time');
            $table->text('delivery_address')->nullable();
            $table->string('delivery_driver_id', 36)->nullable(); // Assuming drivers are also users
            $table->timestamps();

            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
            $table->foreign('delivery_driver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
