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
            'order_items',
            function (Blueprint $table) {
                $table->longText('special_instruction')->nullable();
            }
        );
        Schema::table(
            'addto_carts',
            function (Blueprint $table) {
                $table->longText('special_instruction')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table(
            'order_items',
            function (Blueprint $table) {
                $table->dropColumn('special_instruction');
            }
        );
        Schema::table(
            'addto_carts',
            function (Blueprint $table) {
                $table->dropColumn('special_instruction');
            }
        );
    }
};
