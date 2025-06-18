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
        Schema::create('reservations', function (Blueprint $table) {
            $table->string('id')->primary(); 
            $table->string('company_id');
            $table->string('outlet_id');
            $table->string('customer_id')->nullable();
            $table->string('table_id')->nullable(); 
            $table->timestamp('reservation_time');
            $table->timestamp('end_time');
            $table->integer('number_of_guests');
            $table->string('status'); 
            $table->text('notes')->nullable();
            $table->decimal('deposit_amount', 15, 2)->nullable();
            $table->string('confirmation_token')->unique()->nullable();
            $table->timestamps();

            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            // $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            // $table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
