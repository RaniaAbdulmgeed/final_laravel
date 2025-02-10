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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // VARCHAR(255) NOT NULL
            $table->text('description')->nullable(); // TEXT NULL
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // FK to categories
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->json('size_options')->nullable(); // JSON NULL
            $table->json('color_options')->nullable(); // JSON NULL
            $table->text('image_url')->nullable(); // TEXT NULL
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
        Schema::dropIfExists('products');
    }
};
