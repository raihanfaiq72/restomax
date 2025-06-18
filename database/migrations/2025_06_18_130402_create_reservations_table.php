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
            $table->string('id', 36)->primary(); // UUID
            $table->string('company_id', 36);
            $table->string('outlet_id', 36);
            $table->string('customer_id', 36)->nullable();
            $table->string('table_id', 36)->nullable();
            $table->timestamp('reservation_time');
            $table->timestamp('end_time')->nullable();
            $table->integer('number_of_guests');
            $table->enum('status', ['Pending', 'Confirmed', 'Seated', 'Cancelled', 'No_Show', 'Completed']);
            $table->text('notes')->nullable();
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->string('confirmation_token')->unique()->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
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
