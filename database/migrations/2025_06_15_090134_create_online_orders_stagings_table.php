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
        Schema::create('online_orders_stagings', function (Blueprint $table) {
            $table->id();
            $table->string('platform_name');
            $table->string('external_order_id');
            $table->string('raw_payload');
            $table->string('status');
            $table->integer('internal_order_id');
            $table->timestamp('received_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_orders_stagings');
    }
};
