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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->string('id')->primary(); 
            $table->string('company_id');
            $table->string('outlet_id');
            $table->string('ingredient_id');
            $table->decimal('current_stock', 15, 4); 
            $table->decimal('minimum_stock_level', 15, 4)->nullable();
            $table->timestamp('last_restocked_at')->nullable();
            $table->timestamps();

            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            // $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            // $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('restrict');
            // $table->unique(['outlet_id', 'ingredient_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
