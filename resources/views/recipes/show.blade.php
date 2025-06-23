@extends('layouts.layout')

@section('title', $recipe->title)

@section('content')
<div class="container recipe-detail-container">
    <!-- Header сексция -->
    <div class="recipe-header">
        <div class="recipe-image-container">
            <!-- Основное изображение рецепта -->
            <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->title }}" class="recipe-main-image">
            <div class="recipe-overlay">
                <!-- Быстрая информация о рецепте (время, калории, сложность) -->
                <div class="recipe-quick-info">
                    <span class="info-item"><i class="fas fa-clock"></i> {{ $recipe->cook_time }} мин</span>
                    <span class="info-item"><i class="fas fa-fire"></i> {{ $recipe->calories }} ккал</span>
                    <span class="info-item">
                        <i class="fas fa-signal"></i>
                        @if ($recipe->difficulty == 1)
                            Легко
                        @elseif ($recipe->difficulty == 2)
                            Средне
                        @elseif ($recipe->difficulty == 3)
                            Сложно
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="recipe-title-section">
            <!-- Название рецепта и действия пользователя -->
            <h1 class="recipe-title">{{ $recipe->title }}</h1>
            <div class="recipe-actions">
                <div class="rating-display">
                    <!-- Отображение средней оценки -->
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= ($recipe->ratings_avg_rating ?? 0) ? 'active' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="rating-value">{{ number_format($recipe->ratings_avg_rating ?? 0, 1) }}/5</span>
                </div>
                <!-- Кнопки добавления/удаления из избранного -->
                @if ($favorites && $favorites->contains($recipe->id))
                    <form action="{{ route('recipes.favorite', $recipe->id) }}" method="POST" class="favorite-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-heart"></i> Удалить из избранного
                        </button>
                    </form>
                @else
                    <form action="{{ route('recipes.favorite', $recipe->id) }}" method="POST" class="favorite-form">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="far fa-heart"></i> Добавить в избранное
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="recipe-description-section">
        <!-- Описание рецепта -->
        <p class="recipe-description">{{ $recipe->description }}</p>
    </div>

    <div class="recipe-content-grid">
        <div class="ingredients-section">
            <!-- Список ингредиентов -->
            <h2 class="section-title"><i class="fas fa-list"></i> Ингредиенты</h2>
            <ul class="ingredients-list">
                @foreach ($recipe->ingredients as $ingredient)
                    <li class="ingredient-item">
                        <i class="fas fa-check"></i>
                        <span>
                            {{ $ingredient->name }}
                            @if($ingredient->pivot->quantity)
                                — {{ $ingredient->pivot->quantity }}
                                @if($ingredient->pivot->unit)
                                    {{ $ingredient->pivot->unit->short_name }}
                                @endif
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Секция инструкции -->
        <div class="instructions-section">
            <!-- Пошаговая инструкция приготовления -->
            <h2 class="section-title"><i class="fas fa-utensils"></i> Способ приготовления</h2>
            <div class="instructions-content">
                @php
                    $text = trim($recipe->instructions);
                    // Сначала пробуем разбить по шаблону '1. ... 2. ...'
                    $steps = preg_split('/(?<=\d\.|\d\))\s*/u', $text, -1, PREG_SPLIT_NO_EMPTY);
                    // Если не найдено несколько шагов, разбиваем по переводу строки
                    if (count($steps) <= 1) {
                        $steps = preg_split('/\r?\n|\r/', $text);
                    }
                @endphp
                @if(count($steps) > 1)
                    <ol style="padding-left: 1.2rem;">
                        @foreach($steps as $step)
                            @if(trim($step) !== '')
                                <li>{{ trim($step) }}</li>
                            @endif
                        @endforeach
                    </ol>
                @else
                    <div>{{ $steps[0] }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Секция рейтинга -->
    <div class="rating-section">
        <!-- Блок для выставления оценки рецепту -->
        <h2 class="section-title"><i class="fas fa-star"></i> Оценить рецепт</h2>
        <div class="star-rating" data-recipe-id="{{ $recipe->id }}" data-user-rating="{{ $userRating ?? 0 }}">
            @for ($i = 1; $i <= 5; $i++)
                <i class="fas fa-star star" data-value="{{ $i }}"></i>
            @endfor
        </div>
    </div>

    <!-- Секция комментариев -->
    <div class="comments-section">
        <h2 class="section-title"><i class="fas fa-comments"></i> Комментарии</h2>
        
        <!-- Форма комментариев -->
        <form method="POST" action="{{ route('recipes.comment', $recipe) }}" class="comment-form">
            @csrf
            <div class="form-group">
                <textarea name="text" class="form-control" rows="3" placeholder="Поделитесь своим мнением о рецепте..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Отправить комментарий
            </button>
        </form>

        <!-- Лист комментариев -->
        <div class="comments-list">
            @foreach ($recipe->comments as $comment)
                <div class="comment-card">
                    <div class="comment-header">
                        <div class="comment-user">
                            <i class="fas fa-user-circle"></i>
                            <strong>{{ $comment->user->name }}</strong>
                        </div>
                        <span class="comment-date" style="margin-left:auto;">{{ $comment->created_at->diffForHumans() }}</span>
                        @auth
                            @if(auth()->user()->role_id === 1 || auth()->user()->id === $comment->user_id)
                                <!-- Кнопка редактирования комментария -->
                                <button type="button" class="edit-comment-btn" title="Редактировать" onclick="toggleEditForm('{{ $comment->UniqueID }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Кнопка удаления комментария -->
                                <form method="POST" action="{{ route('comments.destroy', ['comment' => $comment->UniqueID]) }}" style="display:inline; margin-left: 0.5rem;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-comment-btn" title="Удалить" onclick="return confirm('Удалить комментарий?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <div class="comment-content" id="comment-content-{{ $comment->UniqueID }}">
                        {{ $comment->text }}
                    </div>
                    @auth
                        @if(auth()->user()->role_id === 1 || auth()->user()->id === $comment->user_id)
                            <div class="edit-comment-form" id="edit-form-{{ $comment->UniqueID }}" style="display: none;">
                                <!-- Форма редактирования комментария -->
                                <form method="POST" action="{{ route('comments.update', ['comment' => $comment->UniqueID]) }}" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <textarea name="text" class="form-control" rows="3" required>{{ $comment->text }}</textarea>
                                    </div>
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i> Сохранить
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEditForm('{{ $comment->UniqueID }}')">
                                            <i class="fas fa-times"></i> Отмена
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Стили для страницы рецепта -->
<style>
.recipe-detail-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.recipe-header {
    display: block;
    text-align: center;
    margin-bottom: 2rem;
}

.recipe-image-container {
    position: relative;
    width: 100%;
    height: 400px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.recipe-main-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.recipe-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    padding: 2rem 1.5rem 1.5rem;
}

.recipe-quick-info {
    display: flex;
    gap: 2rem;
    color: white;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.recipe-title-section {
    text-align: center;
    margin-top: 2rem;
    margin-bottom: 2rem;
}

.recipe-title {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 1rem;
}

.recipe-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    justify-content: center;
    margin-top: 1rem;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: center;
}

.stars {
    color: #ffd700;
}

.rating-value {
    font-weight: 500;
    color: #666;
}

.recipe-description-section {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 3rem;
}

.recipe-description {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #666;
}

.recipe-content-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.section-title {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ingredients-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding-left: 1.2rem;
    margin: 0;
}

.ingredient-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.08rem;
}

.ingredient-item i {
    color: #28a745;
}

.instructions-content {
    line-height: 1.8;
    color: #444;
}

.rating-section {
    text-align: center;
    margin-bottom: 3rem;
}

.star-rating {
    display: inline-flex;
    gap: 0.5rem;
    font-size: 1.5rem;
    color: #ddd;
}

.star-rating .star {
    cursor: pointer;
    transition: color 0.2s;
}

.star-rating .star:hover,
.star-rating .star.active {
    color: #ffd700;
}

.comments-section {
    max-width: 800px;
    margin: 0 auto;
}

.comment-form {
    margin-bottom: 2rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 1rem;
    resize: vertical;
}

.comments-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.comment-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid var(--border);
}

.comment-header {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin-bottom: 0.5rem;
    gap: 0.5rem;
}

.comment-user {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.comment-date {
    margin-left: auto;
    color: #666;
    font-size: 0.9rem;
}

.comment-content {
    color: #444;
    line-height: 1.5;
}

.edit-comment-btn, .delete-comment-btn {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 0.25rem;
    transition: color 0.2s;
}

.edit-comment-btn:hover {
    color: #007bff;
}

.delete-comment-btn:hover {
    color: #dc3545;
}

.edit-comment-form {
    margin-top: 1rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 4px;
}

@media (max-width: 768px) {
    .recipe-content-grid {
        grid-template-columns: 1fr;
    }

    .recipe-image-container {
        height: 300px;
    }

    .recipe-quick-info {
        flex-direction: column;
        gap: 1rem;
    }

    .recipe-title {
        font-size: 2rem;
    }
}
</style>

<!-- Добавляем стили для темной темы комментариев -->
<style>
.dark-theme .comment-card,
.dark-theme .comment-header,
.dark-theme .comment-content,
.dark-theme .comment-date,
.dark-theme .comment-user strong {
    color: var(--text) !important;
}
.dark-theme .comment-card {
    background: var(--card-bg) !important;
    border: 1px solid var(--border) !important;
}
</style>

<!-- Подключаем скрипт для работы с рейтингом -->
<script src="{{ asset('assets/js/rating.js') }}"></script>

@push('scripts')
<script>
const isGuest = @json($isGuest ?? false);
document.addEventListener('DOMContentLoaded', function() {
    // Обработка клика по звездам рейтинга
    document.querySelectorAll('.star-rating .star').forEach(function(star) {
        star.addEventListener('click', function(e) {
            if (isGuest) {
                window.location.href = '/login';
                return;
            }
        });
    });
});

// Функция для переключения формы редактирования комментария
function toggleEditForm(commentId) {
    const contentDiv = document.getElementById(`comment-content-${commentId}`);
    const editForm = document.getElementById(`edit-form-${commentId}`);
    
    if (contentDiv.style.display !== 'none') {
        contentDiv.style.display = 'none';
        editForm.style.display = 'block';
    } else {
        contentDiv.style.display = 'block';
        editForm.style.display = 'none';
    }
}
</script>
@endpush
@endsection
