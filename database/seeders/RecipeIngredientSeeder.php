<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RecipeIngredient;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Unit;

class RecipeIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Получаем все рецепты
        $recipes = Recipe::all();

        // Получаем все ингредиенты и единицы измерения
        $ingredients = Ingredient::all();
        $units = Unit::all();

        // Для каждого рецепта
        foreach ($recipes as $recipe) {
            // Для каждого рецепта добавляем несколько случайных ингредиентов
            $ingredientCount = rand(1, 10); // случайное количество ингредиентов для рецепта (от 1 до 10)

            // Для каждого ингредиента создаем запись в таблице RecipeIngredient
            for ($i = 0; $i < $ingredientCount; $i++) {
                // Получаем случайный ингредиент и случайную единицу измерения
                $ingredient = $ingredients->random();
                $unit = $units->random();

                // Генерируем случайное количество в зависимости от единицы измерения
                $quantity = match($unit->short_name) {
                    'г' => rand(10, 1000), // от 10 до 1000 грамм
                    'мл' => rand(10, 1000), // от 10 до 1000 миллилитров
                    'шт' => rand(1, 10), // от 1 до 10 штук
                    default => rand(1, 100), // для других единиц измерения
                };

                // Добавляем запись в таблицу RecipeIngredient
                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'ingredient_id' => $ingredient->id,
                    'unit_id' => $unit->id,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
