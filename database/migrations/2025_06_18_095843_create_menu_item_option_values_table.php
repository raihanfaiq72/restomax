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
        Schema::create('menu_item_option_values', function (Blueprint $table) {
            $table->string('id')->primary(); 
            $table->string('menu_item_option_id');
            $table->string('value');
            $table->decimal('price_adjustment', 10, 2)->nullable();
            $table->timestamps();

            // $table->foreign('menu_item_option_id')->references('id')->on('menu_item_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_option_values');
    }
};
