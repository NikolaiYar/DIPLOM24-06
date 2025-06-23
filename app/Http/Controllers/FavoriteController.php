<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Добавить рецепт в избранное
    public function store($recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        // Проверяем, не добавлен ли уже этот рецепт в избранное
        if (Auth::check() && !Favorite::where('user_id', Auth::id())->where('recipe_id', $recipe->id)->exists()) {
            Favorite::create([
                'user_id' => Auth::id(),
                'recipe_id' => $recipe->id,
            ]);
        }

        return back();
    }

    // Удалить рецепт из избранного
    public function destroy($recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        if (Auth::check()) {
            Favorite::where('user_id', Auth::id())->where('recipe_id', $recipe->id)->delete();
        }

        return back();
    }
}
