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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary Key (id)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign Key to users
            $table->decimal('total_price', 10, 2)->default(0); // Total price of order
            $table->enum('status', ['pending', 'shipped', 'delivered', 'canceled'])->default('pending'); // Order status
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending'); // Payment status
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
        Schema::dropIfExists('orders');
    }
};
