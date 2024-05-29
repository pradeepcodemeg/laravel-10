<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name', 50);
            $table->decimal('price', 6, 2);
            $table->integer('quantity');
            $table->string('cover_photo')->nullable();
            $table->text('images')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->enum('delivery_type', ['Hand to Hand', 'shipping Company'])->default('Hand to Hand');

            $table->foreign('platform_id')->on('plateforms')->references('id')->onDelete('cascade');
            $table->foreign('category_id')->on('categories')->references('id')->onDelete('cascade');
            $table->timestamps();
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
}
