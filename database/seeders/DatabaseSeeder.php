<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        Brand::factory(10)->create();

        Category::factory(5)
            ->has(Product::factory(rand(2, 5)))
            ->create();
    }
}
