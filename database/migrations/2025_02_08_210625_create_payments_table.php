<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // FK to orders
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK to users
            $table->enum('payment_method', ['credit_card', 'paypal', 'bank_transfer'])->notNullable(); // Payment method
            $table->decimal('amount', 10, 2)->notNullable(); // Payment amount
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending'); // Payment status
            $table->tinyInteger('state')->default(1); // 1 = active, 0 = deleted
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
