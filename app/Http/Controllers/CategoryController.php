<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    // Список всех категорий
    public function index()
    {
        $categories = Category::paginate(10);
        return view('categories.index', compact('categories'));
    }

    // Просмотр одной категории с её рецептами
    public function show(Category $category)
    {
        $recipes = $category->recipes()->with('user')->latest()->paginate(10);
        return view('categories.show', compact('category', 'recipes'));
    }

    // Форма создания категории (только для админов)
    public function create()
    {
        Log::info('Попытка доступа к форме создания категории');
        return view('categories.create');
    }

    // Сохранение новой категории
    public function store(Request $request)
    {
        Log::info('Запрос на создание категории:', $request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category_images', 'public');
            $validated['image'] = $imagePath;
        }

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        Log::info('Категория успешно создана');

        return redirect()->route('categories.index')->with('success', 'Категория успешно создана!');
    }

    // Форма редактирования категории
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Обновление категории
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если есть
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store('category_images', 'public');
            $validated['image'] = $imagePath;
        }

        $category->update($validated);

        return redirect()->route('categories.show', $category)->with('success', 'Категория обновлена!');
    }

    // Удаление категории
    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Категория удалена!');
    }
}
