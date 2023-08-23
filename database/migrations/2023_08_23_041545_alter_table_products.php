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
            'products',
            function (Blueprint $table) {
                $table->dropConstrainedForeignId('sub_category_id');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'products',
            function (Blueprint $table) {
                $table->unsignedBigInteger('sub_category_id')->nullable();
                $table->foreign('sub_category_id')->on('sub_categories')->references('id');
            }
        );
    }
};
