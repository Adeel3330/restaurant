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
        Schema::create('flavour_addons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flavour_id');
            $table->unsignedBigInteger('addon_id');
            $table->foreign('flavour_id')->references('id')->on('product_flavours');
            $table->foreign('addon_id')->references('id')->on('addons');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flavour_addons');
    }
};
