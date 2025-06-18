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
        Schema::create('companies', function (Blueprint $table) {
            $table->string('id')->primary(); 
            $table->string('name');
            $table->string('business_type'); 
            $table->string('scale');
            $table->text('address');
            $table->string('phone');
            $table->string('email');
            $table->string('logo_url')->nullable();
            $table->json('operating_hours')->nullable(); 
            $table->string('current_subscription_id')->nullable(); 

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
