<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rating;
use App\Models\Recipe;
use App\Models\User;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::all();
        $recipes = Recipe::all();

        foreach ($recipes as $recipe) {
            // Пропускаем, если нет пользователей
            if ($users->isEmpty()) {
                continue;
            }

            // Выбираем случайного пользователя
            $user = $users->random();

            // Проверяем, не оставлял ли этот пользователь уже рейтинг
            $existing = Rating::where('user_id', $user->id)
                ->where('recipe_id', $recipe->id)
                ->first();

            if (!$existing) {
                Rating::create([
                    'rating' => rand(1, 5),
                    'recipe_id' => $recipe->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
