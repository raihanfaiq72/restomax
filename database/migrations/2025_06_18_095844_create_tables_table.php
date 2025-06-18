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
        Schema::create('tables', function (Blueprint $table) {
            $table->string('id')->primary(); 
            $table->string('outlet_id');
            $table->string('table_number'); 
            $table->integer('capacity')->nullable();
            $table->string('qr_code_url')->unique()->nullable(); 
            $table->integer('layout_x')->nullable(); 
            $table->integer('layout_y')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
