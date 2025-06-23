@extends('layouts.layout')

@section('title', 'Главная страница')

@section('content')
    <div class="container main-container">
        <!-- Приветственный баннер -->
        <div class="welcome-banner">
            <h1>Добро пожаловать на кулинарный портал!</h1>
            <p>Откройте для себя лучшие рецепты, делитесь своими блюдами и вдохновляйтесь новыми идеями каждый день.</p>
        </div>

        <!-- Вкладки категорий -->
        <div class="category-recipes mt-5">
            <!-- Контент для каждой категории -->
            <div class="tab-content mt-4">
                @foreach ($categories as $category)
                    <div class="tab-pane fade" id="category-{{ $category->id }}">
                        <h2 class="section-title">{{ $category->name }}</h2>
                        <div class="recipe-grid">
                            @foreach ($recipesByCategory[$category->id] as $recipe)
                                <div class="recipe-card">
                                    <div class="recipe-image">
                                        <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->title }}" class="recipe-img">
                                    </div>
                                    <div class="recipe-info">
                                        <h3 class="recipe-title">{{ $recipe->title }}</h3>
                                        <p class="recipe-desc">{{ Str::limit($recipe->description, 60) }}</p>
                                        <div class="recipe-meta">
                                            <span class="cook-time">⏱ {{ $recipe->cook_time }} мин.</span>
                                            <span class="cook-time">⭐ {{ $recipe->ratings->avg('rating') ?? 'Нет оценок' }}</span>
                                            <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-primary btn-sm">Подробнее</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Новые рецепты -->
        <div class="new-recipes mt-5">
            <h2 class="section-title">Новые рецепты</h2>
            <div class="recipe-grid">
                @foreach ($newRecipes as $recipe)
                    <div class="recipe-card">
                        <div class="recipe-image">
                            <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->title }}" class="recipe-img">
                        </div>
                        <div class="recipe-info">
                            <h3 class="recipe-title">{{ $recipe->title }}</h3>
                            <p class="recipe-desc">{{ Str::limit($recipe->description, 60) }}</p>
                            <div class="recipe-meta">
                                <span class="cook-time">⏱ {{ $recipe->cook_time }} мин.</span>
                                <span class="cook-time">⭐ {{ $recipe->ratings->avg('rating') ?? 'Нет оценок' }}</span>
                                <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-primary btn-sm">Подробнее</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Рецепты с высокой оценкой -->
        <div class="high-rated-recipes mt-5">
            <h2 class="section-title">Рецепты с высокой оценкой</h2>
            <div class="recipe-grid">
                @foreach ($highRatedRecipes as $recipe)
                    <div class="recipe-card">
                        <div class="recipe-image">
                            <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->title }}" class="recipe-img">
                        </div>
                        <div class="recipe-info">
                            <h3 class="recipe-title">{{ $recipe->title }}</h3>
                            <p class="recipe-desc">{{ Str::limit($recipe->description, 60) }}</p>
                            <div class="recipe-meta">
                                <span class="cook-time">⏱ {{ $recipe->cook_time }} мин.</span>
                                <span class="cook-time">⭐ {{ $recipe->ratings->avg('rating') ?? 'Нет оценок' }}</span>
                                <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-primary btn-sm">Подробнее</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
