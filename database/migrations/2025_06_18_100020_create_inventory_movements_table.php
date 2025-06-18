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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->string('id')->primary(); 
            $table->string('company_id');
            $table->string('ingredient_id');
            $table->string('outlet_id_from')->nullable(); 
            $table->string('outlet_id_to')->nullable(); 
            $table->decimal('quantity', 15, 4);
            $table->string('type');
            $table->string('reference_id')->nullable(); 
            $table->text('notes')->nullable();
            $table->timestamp('movement_at');
            $table->timestamps();

            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            // $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('restrict');
            // $table->foreign('outlet_id_from')->references('id')->on('outlets')->onDelete('set null');
            // $table->foreign('outlet_id_to')->references('id')->on('outlets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
