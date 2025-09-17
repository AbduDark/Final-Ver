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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique();
            $table->enum('transfer_type', ['instant_payment', 'recharge_cards', 'vodafone_cash', 'etisalat_cash', 'orange_cash']);
            $table->decimal('amount', 10, 2);
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
