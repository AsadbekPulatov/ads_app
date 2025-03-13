<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ["name" => "Category 1", "parent_id" => null],
            ["name" => "Sub Cat 1", "parent_id" => 1],
            ["name" => "Category 2", "parent_id" => null],
        ];

        Category::insert($categories);
    }
}
