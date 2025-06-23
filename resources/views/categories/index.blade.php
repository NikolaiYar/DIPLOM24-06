@extends('layouts.layout')

@section('title', 'Список категорий')

@section('content')
    <div class="container category-container">
        <div class="category-header" style="display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="width: 200px;"></div>
            <h1 class="category-main-title" style="flex:1; text-align:center; margin:0;">Категории</h1>
            @auth
                @if(auth()->user()->role_id === 1)
                    <a href="{{ route('categories.create') }}" class="add-category-btn" style="min-width:200px; display:inline-block; text-align:right;">
                        <i class="fas fa-plus"></i> Добавить категорию
                    </a>
                @else
                    <div style="width: 200px;"></div>
                @endif
            @endauth
        </div>

        <div class="category-grid">
            @foreach($categories as $category)
                <div class="recipe-card category-card-center">
                    <a href="{{ route('categories.show', $category) }}" class="category-link" style="display:block;text-decoration:none;">
                        <div class="recipe-info">
                            <h3 class="recipe-title" style="margin-bottom:0.75rem;">{{ $category->name }}</h3>
                            <p class="recipe-desc">{{ Str::limit($category->description, 120) }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
