<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            IngredientSeeder::class,
            UnitSeeder::class,
            RecipeSeeder::class,
            RecipeIngredientSeeder::class,
            RatingSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
