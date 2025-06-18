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
        Schema::create('expenses', function (Blueprint $table) {
            $table->string('id')->primary(); 
            $table->string('company_id');
            $table->string('outlet_id')->nullable();
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->string('category'); 
            $table->string('payment_method');
            $table->timestamps();

            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            // $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
