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
        Schema::create('order_item_options', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->string('order_item_id', 36);
            $table->string('menu_item_option_value_id', 36);
            $table->decimal('price_adjustment_applied', 10, 2)->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->foreign('menu_item_option_value_id')->references('id')->on('menu_item_option_values')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_options');
    }
};
