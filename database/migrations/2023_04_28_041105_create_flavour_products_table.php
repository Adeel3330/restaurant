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
       
        Schema::create('flavour_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flavour_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('flavour_id')->references('id')->on('product_flavours');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flavour_products');
    }
};
