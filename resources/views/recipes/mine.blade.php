@extends('layouts.layout')

@section('content')
    <div class="container recipe-container">
        <div class="recipe-header d-flex align-items-center">
            <h1 class="recipe-main-title">Мои рецепты</h1>
            <a href="{{ route('recipes.create') }}" class="create-recipe-btn btn btn-primary">+ Добавить рецепт</a>
        </div>
        @if($recipes->isEmpty())
            <div class="text-center">
                <p>У вас пока нет добавленных рецептов.</p>
            </div>
        @else
            <div class="recipe-grid mine">
                @foreach($recipes as $recipe)
                    <div class="recipe-card mine">
                        <div class="recipe-image mine">
                            @if($recipe->image)
                                @if(Str::startsWith($recipe->image, '/storage/'))
                                    <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->title }}" class="recipe-img">
                                @else
                                    <img src="{{ asset('assets/images/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="recipe-img">
                                @endif
                            @else
                                <img src="{{ asset('assets/images/default-recipe.jpg') }}" alt="{{ $recipe->title }}" class="recipe-img">
                            @endif
                        </div>
                        <div class="recipe-info">
                            <h3 class="recipe-title mine">{{ $recipe->title }}</h3>
                            <p class="recipe-desc mine">{{ Str::limit($recipe->description, 60) }}</p>
                            <div class="recipe-meta">
                                <span class="cook-time">⏱ {{ $recipe->cook_time }} мин.</span>
                                <span class="cook-time">⭐ {{ $recipe->ratings->avg('rating') ? number_format($recipe->ratings->avg('rating'), 1) : 'Нет оценок' }}</span>
                            </div>
                            <div class="my-recipe-actions">
                                <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-success btn-sm">Редактировать</a>
                                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить этот рецепт?')">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>
@endsection

@push('styles')
@endpush
