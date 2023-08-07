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
        Schema::table(
            'addons',
            function (Blueprint $table) {
              $table->dropConstrainedForeignId('category_id');
              $table->dropConstrainedForeignId('sub_category_id');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create(
            'addons',
            function (Blueprint $table) {
           
        $table->unsignedBigInteger('category_id');
        $table->foreign('category_id')->references('id')->on('categories')->change();
        $table->unsignedBigInteger('sub_category_id');
        $table->foreign('sub_category_id')->references('id')->on('sub_categories')->change();
            });
    }
};
