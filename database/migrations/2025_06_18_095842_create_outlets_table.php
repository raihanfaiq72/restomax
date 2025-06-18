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
        Schema::create('outlets', function (Blueprint $table) {
            $table->string('id')->primary(); // UUID
            $table->string('company_id');
            $table->string('name');
            $table->text('address');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
