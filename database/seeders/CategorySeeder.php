<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate([
            'name' => 'Entertainment',
        ]);

        Category::firstOrCreate([
            'name' => 'Educational & Business',
        ]);

        Category::firstOrCreate([
            'name' => 'Cultural & Arts',
        ]);

        Category::firstOrCreate([
            'name' => 'Sports & Fitness',
        ]);

        Category::firstOrCreate([
            'name' => 'Technology & Innovation',
        ]);

        Category::firstOrCreate([
            'name' => 'Travel & Adventure',
        ]);
    }
}

