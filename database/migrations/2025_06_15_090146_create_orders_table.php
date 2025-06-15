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
            $table->id();
            $table->string('order_number');
            $table->integer('user_id');
            $table->integer('customer_id');
            $table->integer('table_id');
            $table->enum('order_type',['dine-in','take away','online delivery']);
            $table->enum('status',['pending','processed','completed','cancelled']);
            $table->float('sub_total');
            $table->float('discount_amount');
            $table->float('tax_amount');
            $table->float('final_amount');
            $table->timestamps();
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
