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
            $table->string('id', 36)->primary(); // UUID
            $table->string('name');
            $table->enum('business_type', ['Burjo', 'Cafe', 'Restaurant', 'Bakery', 'Food Truck', 'Catering', 'Dapur Sentral']);
            $table->enum('scale', ['UMKM_Mikro', 'UMKM_Kecil', 'Menengah', 'Besar']);
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo_url')->nullable();
            $table->json('operating_hours')->nullable();
            $table->string('current_subscription_id', 36)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key ke company_subscriptions
            $table->foreign('current_subscription_id')->references('id')->on('company_subscriptions')->onDelete('set null');
        });

        // Setelah tabel companies dibuat, tambahkan foreign key company_id ke company_subscriptions
        Schema::table('company_subscriptions', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
