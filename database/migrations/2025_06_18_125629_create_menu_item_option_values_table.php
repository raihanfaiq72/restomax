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
            $table->string('id', 36)->primary(); // UUID
            $table->string('menu_item_option_id', 36);
            $table->string('value');
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->timestamps();

            // Foreign key
            $table->foreign('menu_item_option_id')->references('id')->on('menu_item_options')->onDelete('cascade');
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
