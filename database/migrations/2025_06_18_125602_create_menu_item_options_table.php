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
        Schema::create('menu_item_options', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->string('company_id', 36);
            $table->string('name');
            $table->enum('type', ['single_select', 'multi_select', 'text_input']);
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_options');
    }
};
