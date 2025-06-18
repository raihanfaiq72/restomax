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
            $table->string('id', 36)->primary(); // UUID
            $table->string('company_id', 36);
            $table->string('ingredient_id', 36);
            $table->string('outlet_id_from', 36)->nullable();
            $table->string('outlet_id_to', 36)->nullable();
            $table->decimal('quantity', 10, 2);
            $table->enum('type', ['Purchase', 'Sale_Consumption', 'Waste', 'Transfer_In', 'Transfer_Out', 'Adjustment']);
            $table->string('reference_id')->nullable(); // e.g., order_id, purchase_id
            $table->text('notes')->nullable();
            $table->timestamp('movement_at');
            $table->timestamps();

            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
            $table->foreign('outlet_id_from')->references('id')->on('outlets')->onDelete('set null');
            $table->foreign('outlet_id_to')->references('id')->on('outlets')->onDelete('set null');
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
