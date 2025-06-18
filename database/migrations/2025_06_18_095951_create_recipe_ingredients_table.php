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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->string('recipe_id');
            $table->string('ingredient_id');
            $table->decimal('quantity_required', 15, 4); 
            $table->primary(['recipe_id', 'ingredient_id']); 
            

            // $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
            // $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
