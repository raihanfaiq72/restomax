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
        Schema::create('menu_item_option_associations', function (Blueprint $table) {
            $table->string('menu_item_id', 36);
            $table->string('menu_item_option_id', 36);
            $table->primary(['menu_item_id', 'menu_item_option_id']);

            // Foreign keys
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->foreign('menu_item_option_id')->references('id')->on('menu_item_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_option_associations');
    }
};
