<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function create()
    {
        if (Auth::user()->role_id !== 1) abort(403);
        return view('ingredients.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role_id !== 1) abort(403);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
        ]);
        Ingredient::create($validated);
        return redirect()->route('ingredients.create')->with('success', 'Ингредиент успешно добавлен!');
    }
}
