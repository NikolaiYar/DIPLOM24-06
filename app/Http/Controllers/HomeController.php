<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем новые рецепты (например, последние 5)
        $newRecipes = Recipe::latest()->take(5)->get();

        // Получаем все категории
        $categories = Category::all();

        // Получаем рецепты с высокой оценкой (рейтинг 4 и выше)
        $highRatedRecipes = Recipe::whereHas('ratings', function ($query) {
            $query->where('rating', '>=', 4);
        })->with('ratings')  // Загружаем связанные оценки
        ->get();

        $recipesByCategory = [];

        foreach ($categories as $category) {
            // Получаем рецепты для каждой категории через связь многие ко многим
            $recipesByCategory[$category->id] = $category->recipes()->take(5)->get();
        }

        // Получаем все рецепты с пагинацией
        $recipes = Recipe::paginate(10);

        // Передаем все переменные в представление
        return view('welcome', compact('newRecipes', 'highRatedRecipes', 'recipes', 'categories', 'recipesByCategory'));
    }
}
