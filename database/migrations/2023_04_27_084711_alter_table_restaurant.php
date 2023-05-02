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
        Schema::create('restaurants_timings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('opening_time');
            $table->time('closing_time');
            $table->timestamps();
        });

        // Schema::table('restaurants', function (Blueprint $table) {
        //     $table->string('address');
        //     $table->unsignedBigInteger('week_id')->nullable();
        //     $table->foreign('week_id')->references('id')->on('restaurants_timings')->change();
        //     $table->string('phone_no');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::create('restaurants', function (Blueprint $table) {
        //     $table->dropColumn('address');
        //     $table->dropConstrainedForeignId('week_id');
        //     $table->dropColumn('phone_no');
        // });
        
        Schema::dropIfExists('restaurants_timings');
    }
};
