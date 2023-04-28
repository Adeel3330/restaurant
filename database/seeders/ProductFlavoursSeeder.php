<?php

namespace Database\Seeders;

use App\Models\ProductFlavours;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductFlavoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductFlavours::insert([
            [
                'name'=>'Cold',
            ],
            [
                'name' => 'Mild',
            ],
            [
                'name' => 'Hot',
            ]
        ]);
    }
}
