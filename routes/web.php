<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IngredientController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/recipes/autocomplete', [RecipeController::class, 'autocomplete'])->name('recipes.autocomplete');
// Рецепты (публичные + защищенные)
Route::prefix('recipes')->group(function () {
    Route::get('/', [RecipeController::class, 'index'])->name('recipes.index');

    Route::middleware('auth')->group(function () {
        Route::get('/create', [RecipeController::class, 'create'])->name('recipes.create');
        Route::post('/', [RecipeController::class, 'store'])->name('recipes.store');
        Route::get('/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
        Route::put('/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
        Route::delete('/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
        Route::get('/recipes/mine', [RecipeController::class, 'mine'])->name('recipes.mine');
        Route::resource('recipes', RecipeController::class)->except(['index', 'show']);

        Route::post('/rate-recipe', [RecipeController::class, 'rate'])->name('recipes.rate');
        Route::post('/{recipe}/comment', [RecipeController::class, 'comment'])->name('recipes.comment');

        Route::post('/{recipe}/favorite', [FavoriteController::class, 'store'])->name('recipes.favorite');
        Route::delete('/{recipe}/favorite', [FavoriteController::class, 'destroy'])->name('recipes.unfavorite');
    });

    Route::get('/{recipe}', [RecipeController::class, 'show'])->name('recipes.show'); // <-- ставим его в самый конец!
    
});

// Категории (публичные + админка)
Route::prefix('categories')->group(function () {
    // Публичные маршруты
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');

    // Маршруты для администраторов
    Route::middleware(['auth'])->group(function () {
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show');
    });
});

// Ингредиенты (только для админа)
Route::middleware(['auth'])->group(function () {
    Route::get('/ingredients/create', [IngredientController::class, 'create'])->name('ingredients.create');
    Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
});

// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Профиль и выход
Route::middleware('auth')->group(function () {
    // Выход из аккаунта
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Профиль
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/password', [ProfileController::class, 'editPassword'])->name('profile.password');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});

Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::put('/comment/{comment}', [CommentController::class, 'update'])->name('comments.update');
