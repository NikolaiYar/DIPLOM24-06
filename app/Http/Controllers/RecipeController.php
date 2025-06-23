<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Ingredient;
use App\Models\Category;
use App\Models\Unit;


class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category');

        $recipesQuery = Recipe::query();

        if ($search) {
            $recipesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $recipesQuery->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }


        $recipes = $recipesQuery->paginate(9);

        // Новые рецепты
        $newRecipes = Recipe::orderBy('created_at', 'desc')->limit(5)->get();

        // Высоко оценённые рецепты
        $highRatedRecipes = Recipe::whereHas('ratings', function ($query) {
            $query->where('rating', '>=', 4);
        })->get();

        // ✅ Получаем все категории
        $categories = Category::all();

        return view('recipes.index', compact(
            'recipes',
            'newRecipes',
            'highRatedRecipes',
            'categories'
        ));
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|string',
            'cook_time' => 'required|integer|min:1',
            'difficulty' => 'required|integer|in:1,2,3',
            'calories' => 'required|integer|min:0',
            'instructions' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ingredients_json' => ['required', 'json', function ($attribute, $value, $fail) {
                $ingredients = json_decode($value, true);
                if (empty($ingredients)) {
                    $fail('Добавьте хотя бы один ингредиент.');
                }
            }],
        ],
        [
            'title.required' => 'Поле Название рецепта обязательно для заполнения.',
            'title.max' => 'Название рецепта не должно превышать :max символов.',

            'description.required' => 'Поле Описание рецепта обязательно для заполнения.',
            'description.string' => 'Описание рецепта должно быть строкой.',

            'cook_time.required' => 'Поле Время приготовления обязательно для заполнения.',
            'cook_time.integer' => 'Время приготовления должно быть целым числом.',
            'cook_time.min' => 'Время приготовления должно быть не менее :min.',

            'difficulty.required' => 'Поле Сложность обязательно для заполнения.',
            'difficulty.integer' => 'Сложность должна быть числом.',
            'difficulty.in' => 'Выберите корректное значение для сложности.',

            'calories.required' => 'Поле Калорийность обязательно для заполнения.',
            'calories.integer' => 'Калорийность должна быть целым числом.',
            'calories.min' => 'Калорийность должна быть не менее :min.',

            'instructions.required' => 'Поле Инструкция по приготовлению обязательно для заполнения.',
            'instructions.string' => 'Инструкция по приготовлению должна быть строкой.',

            'categories.required' => 'Выберите хотя бы одну категорию.',
            'categories.array' => 'Категории должны быть выбраны в виде списка.',
            'categories.*.exists' => 'Одна или несколько выбранных категорий не существуют.',

            'image.required' => 'Выберите изображение рецепта.',
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Изображение должно быть в формате JPEG, PNG или JPG.',
            'image.max' => 'Размер изображения не должен превышать 2 МБ.',

            'ingredients_json.required' => 'Добавьте хотя бы один ингредиент.',
            'ingredients_json.json' => 'Неверный формат данных ингредиентов.',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');
            $validated['image'] = '/storage/' . $path;
        }
        $validated['user_id'] = auth()->id();
        $recipe = Recipe::create($validated);
        $ingredientData = [];
        if ($request->filled('ingredients_json')) {
            $ingredients = json_decode($request->input('ingredients_json'), true);
            foreach ($ingredients as $item) {
                $ingredientData[$item['id']] = [
                    'quantity' => $item['quantity'] ?? null,
                    'unit_id' => $item['unit_id'] ?? 1
                ];
            }
        } else if ($request->has('ingredients')) {
            foreach ($request->input('ingredients', []) as $ingredientId => $data) {
                if (!empty($data['checked'])) {
                    $ingredientData[$ingredientId] = [
                        'quantity' => $data['quantity'] ?? null,
                        'unit_id' => $data['unit_id'] ?? 1
                    ];
                }
            }
        }
        $recipe->ingredients()->sync($ingredientData);
        $recipe->categories()->sync($request->categories);
        return redirect()->route('recipes.index')->with('success', 'Рецепт успешно добавлен!');
    }

    public function mine()
    {
        $recipes = Auth::user()->recipes()->latest()->paginate(10);
        return view('recipes.mine', compact('recipes'));
    }

    public function rate(Request $request)
    {
        // Валидация входных данных
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'recipe_id' => 'required|exists:recipes,id',
        ]);

        // Получаем рецепт
        $recipe = Recipe::find($validated['recipe_id']);

        // Сохраняем или обновляем рейтинг для рецепта
        $rating = Rating::where('recipe_id', $recipe->id)
            ->where('user_id', auth()->id()) // Учитываем, что только авторизованный пользователь может поставить оценку
            ->first();

        if ($rating) {
            // Если рейтинг уже есть, обновляем его
            $rating->rating = $validated['rating'];
            $rating->save();
        } else {
            // Если нет, создаем новый
            Rating::create([
                'recipe_id' => $recipe->id,
                'user_id' => auth()->id(),
                'rating' => $validated['rating'],
            ]);
        }

        return response()->json(['message' => 'Оценка успешно обновлена']);
    }

    public function comment(Request $request, Recipe $recipe)
    {
        $request->validate([
            'text' => 'required|string|max:1000',  // Валидация текста комментария
        ]);

        // Сохраняем комментарий
        $recipe->comments()->create([
            'user_id' => auth()->id(),
            'text' => $request->input('text'),
        ]);

        // Перенаправляем обратно на страницу рецепта
        return back();
    }


    public function show(Recipe $recipe)
    {
        $userRating = null;
        $favorites = null;
        $isGuest = !Auth::check();
        if (Auth::check()) {
            $userRating = $recipe->ratings()->where('user_id', Auth::id())->value('rating');
            $favorites = Auth::user()->favorites;
        }
        $recipe->load(['ingredients', 'comments.user']);
        $recipe->loadAvg('ratings', 'rating');
        foreach ($recipe->ingredients as $ingredient) {
            $ingredient->pivot->load('unit');
        }
        return view('recipes.show', compact('recipe', 'userRating', 'favorites', 'isGuest'));
    }

    public function create()
    {
        $ingredients = Ingredient::all();
        $categories = Category::all();
        $units = Unit::all(); // Получаем все единицы измерения
        return view('recipes.create', compact('ingredients', 'categories', 'units'));
    }
    public function edit(Recipe $recipe)
    {
        $categories = Category::all();
        $ingredients = Ingredient::all();
        $units = Unit::all();
        if ($recipe->user_id != auth()->id()) {
            return redirect()->route('recipes.index')->with('error', 'Вы не можете редактировать чужой рецепт.');
        }
        $initialSelected = $recipe->ingredients->map(function($i) {
            return [
                'id' => $i->id,
                'name' => $i->name,
                'quantity' => $i->pivot->quantity,
                'unit_id' => $i->pivot->unit_id
            ];
        });
        return view('recipes.edit', compact('recipe', 'categories', 'ingredients', 'units', 'initialSelected'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        if ($recipe->user_id != auth()->id()) {
            return redirect()->route('recipes.index')->with('error', 'Вы не можете редактировать чужой рецепт.');
        }
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|string',
            'cook_time' => 'required|integer|min:1',
            'difficulty' => 'required|integer|in:1,2,3',
            'calories' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'instructions' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ingredients_json' => ['required', 'json', function ($attribute, $value, $fail) {
                $ingredients = json_decode($value, true);
                if (empty($ingredients)) {
                    $fail('Добавьте хотя бы один ингредиент.');
                }
            }],
        ],
        [
            'title.required' => 'Поле Название рецепта обязательно для заполнения.',
            'title.max' => 'Название рецепта не должно превышать :max символов.',

            'description.required' => 'Поле Описание рецепта обязательно для заполнения.',
            'description.string' => 'Описание рецепта должно быть строкой.',

            'cook_time.required' => 'Поле Время приготовления обязательно для заполнения.',
            'cook_time.integer' => 'Время приготовления должно быть целым числом.',
            'cook_time.min' => 'Время приготовления должно быть не менее :min.',

            'difficulty.required' => 'Поле Сложность обязательно для заполнения.',
            'difficulty.integer' => 'Сложность должна быть числом.',
            'difficulty.in' => 'Выберите корректное значение для сложности.',

            'calories.required' => 'Поле Калорийность обязательно для заполнения.',
            'calories.integer' => 'Калорийность должна быть целым числом.',
            'calories.min' => 'Калорийность должна быть не менее :min.',

            'instructions.required' => 'Поле Инструкция по приготовлению обязательно для заполнения.',
            'instructions.string' => 'Инструкция по приготовлению должна быть строкой.',

            'categories.required' => 'Выберите хотя бы одну категорию.',
            'categories.array' => 'Категории должны быть выбраны в виде списка.',
            'categories.*.exists' => 'Одна или несколько выбранных категорий не существуют.',

            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Изображение должно быть в формате JPEG, PNG или JPG.',
            'image.max' => 'Размер изображения не должен превышать 2 МБ.',

            'ingredients_json.required' => 'Добавьте хотя бы один ингредиент.',
            'ingredients_json.json' => 'Неверный формат данных ингредиентов.',
        ]);
        if ($request->hasFile('image')) {
            if ($recipe->image) {
                Storage::delete(str_replace('/storage/', 'public/', $recipe->image));
            }
            $path = $request->file('image')->store('recipes', 'public');
            $validated['image'] = '/storage/' . $path;
        }
        $recipe->update($validated);
        $recipe->categories()->sync($request->input('categories', []));
        // --- ingredients ---
        $ingredientData = [];
        if ($request->filled('ingredients_json')) {
            $ingredients = json_decode($request->input('ingredients_json'), true);
            foreach ($ingredients as $item) {
                $ingredientData[$item['id']] = [
                    'quantity' => $item['quantity'] ?? null,
                    'unit_id' => $item['unit_id'] ?? 1
                ];
            }
        } else if ($request->has('ingredients')) {
            foreach ($request->input('ingredients', []) as $ingredientId => $data) {
                if (!empty($data['checked'])) {
                    $ingredientData[$ingredientId] = [
                        'quantity' => $data['quantity'] ?? null,
                        'unit_id' => $data['unit_id'] ?? 1
                    ];
                }
            }
        }
        $recipe->ingredients()->sync($ingredientData);
        return redirect()->route('recipes.show', $recipe)->with('success', 'Рецепт успешно обновлен!');
    }


    public function destroy(Recipe $recipe)
    {
        // Удаляем все комментарии, связанные с рецептом
        $recipe->comments()->delete();

        // Удаляем все связи с ингредиентами
        $recipe->ingredients()->detach();

        // Удаляем сам рецепт
        $recipe->delete();

        return redirect()->route('recipes.index')->with('success', 'Рецепт успешно удалён');
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        if (!$query || mb_strlen($query) < 1) {
            return response()->json([]);
        }
        $recipes = Recipe::where('title', 'like', "{$query}%")
            ->limit(8)
            ->get(['id', 'title']);
        return response()->json($recipes);
    }

}
