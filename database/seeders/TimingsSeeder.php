<?php

namespace Database\Seeders;

use App\Models\RestaurantsTimings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timings = RestaurantsTimings::insert([
            [
                'name' => 'monday',
                'opening_time' => '8:00',
                'closing_time' => '20:00'
            ],
            [
                'name' => 'tuesday',
                'opening_time' => '8:00',
                'closing_time' => '20:00'
            ],
            [
                'name' => 'wednesday',
                'opening_time' => '8:00',
                'closing_time' => '20:00'
            ],
            [
                'name' => 'thursday',
                'opening_time' => '8:00',
                'closing_time' => '20:00'
            ],
            [
                'name' => 'friday',
                'opening_time' => '8:00',
                'closing_time' => '20:00'
            ],
            [
                'name' => 'saturday',
                'opening_time' => '8:00',
                'closing_time' => '20:00'
            ],
            [
                'name' => 'sunday',
                'opening_time' => '8:00',
                'closing_time' => '20:00'
            ]
            ]);   
    }
}
