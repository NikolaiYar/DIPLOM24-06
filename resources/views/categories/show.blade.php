@extends('layouts.layout')

@section('title', 'Категория: ' . $category->name)

@section('content')
    <div class="container category-container category-show">
        <a href="{{ route('categories.index') }}" class="btn btn-secondary back-btn" style="margin-left: 1rem; margin-bottom: 1.2rem;">Назад</a>
        <h1 class="category-title">Рецепты в категории: {{ $category->name }}</h1>

        @if($recipes->isEmpty())
            <div class="text-center" style="font-size: 1.22rem; color: #b0b4bb; margin: 2.5rem 0 2rem 0; text-align: center;">В этой категории пока нет рецептов.</div>
        @endif

        <div class="recipe-grid">
            @foreach($recipes as $recipe)
                <div class="recipe-card">
                    <div class="recipe-image">
                        @if($recipe->image)
                            <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->title }}" class="recipe-img">
                        @else
                            <img src="{{ asset('assets/images/default-recipe.jpg') }}" alt="{{ $recipe->title }}" class="recipe-img">
                        @endif
                    </div>
                    <div class="recipe-info">
                        <h3 class="recipe-title">{{ $recipe->title }}</h3>
                        <p class="recipe-desc">{{ Str::limit($recipe->description, 60) }}</p>
                        <div class="recipe-meta">
                            <span class="cook-time">⏱ {{ $recipe->cook_time }} мин.</span>
                            <span class="cook-time">⭐ {{ $recipe->ratings->avg('rating') ?? 'Нет оценок' }}</span>
                            <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-primary btn-sm">Подробнее</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-container mt-4">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{-- Проверяем, есть ли предыдущая страница --}}
                    @if ($recipes->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Предыдущая</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $recipes->previousPageUrl() }}" aria-label="Предыдущая">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif

                    {{-- Страницы пагинации --}}
                    @foreach ($recipes->links()->elements[0] as $page => $url)
                        <li class="page-item {{ $recipes->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Проверяем, есть ли следующая страница --}}
                    @if ($recipes->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $recipes->nextPageUrl() }}" aria-label="Следующая">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Следующая</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
@endsection



