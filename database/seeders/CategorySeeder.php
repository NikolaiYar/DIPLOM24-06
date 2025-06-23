<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = ['Завтраки', 'Салаты', 'Супы', 'Десерты', 'Основные блюда', 'Разное'];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
