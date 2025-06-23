@extends('layouts.layout')

@section('title', 'Список рецептов')

@section('content')
    <div class="main-container" style="padding: 2.5rem 0 2rem 0;">
        <div class="container recipe-container">
            {{-- Верхняя панель с поиском и фильтрами --}}
            <div class="recipes-header">
                <div class="recipes-header-top">
                    <h1 class="recipes-title">Список рецептов</h1>
                    @auth
                        <a href="{{ route('recipes.mine') }}" class="btn btn-primary">
                            <i class="fas fa-book"></i> Мои рецепты
                        </a>
                    @endauth
                </div>
                
                <div class="recipes-filters">
                    <form method="GET" action="{{ route('recipes.index') }}" class="filters-form">
                        <div class="filters-row">
                            <div class="search-group">
                                <form method="GET" action="{{ route('recipes.index') }}" class="search-form" autocomplete="off" style="position: relative;">
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        class="search-input"
                                        placeholder="Поиск рецептов..."
                                        id="recipe-search-input"
                                        autocomplete="off"
                                    >
                                    <div id="autocomplete-list" class="autocomplete-items"></div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="category-group">
                                <select
                                    id="category"
                                    name="category"
                                    class="form-select category-select"
                                >
                                    <option value="">Все категории</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Применить
                                </button>
                                <a href="{{ route('recipes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Сбросить
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Сетка рецептов --}}
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

            {{-- Пагинация --}}
            <div class="pagination-container mt-4">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
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

                        @foreach ($recipes->links()->elements[0] as $page => $url)
                            <li class="page-item {{ $recipes->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

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
    </div>
@endsection

@push('styles')
<style>
.recipe-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
    gap: 2rem !important;
    margin: 0 auto 2rem auto;
    padding: 0;
    justify-content: center;
}
.recipe-card {
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s, transform 0.2s;
}
.recipe-card:hover {
    box-shadow: 0 8px 24px rgba(58,90,143,0.12);
    transform: translateY(-4px);
}
.recipe-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}
.recipe-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 8px 8px 0 0;
}
@media (max-width: 900px) {
    .recipe-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)) !important;
    }
    .recipe-image {
        height: 160px;
    }
}
@media (max-width: 600px) {
    .recipe-grid {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    .recipe-image {
        height: 120px;
    }
}
.autocomplete-items {
    position: absolute;
    border: 1px solid #ddd;
    border-top: none;
    z-index: 99;
    top: 100%;
    left: 0;
    right: 0;
    background-color: #fff;
    border-radius: 0 0 4px 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-height: 200px;
    overflow-y: auto;
}

.autocomplete-item {
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.autocomplete-item:hover {
    background-color: #f8f9fa;
}

.autocomplete-item.active {
    background-color: #e9ecef;
}

.search-group {
    position: relative;
}

.search-input {
    padding-right: 40px;
}

.search-form {
    position: relative;
    width: 100%;
}

.recipe-grid .recipe-card {
    min-width: 300px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('recipe-search-input');
    const list = document.getElementById('autocomplete-list');
    let timer;
    let currentFocus = -1;

    input.addEventListener('input', function() {
        clearTimeout(timer);
        const query = this.value.trim();
        if (query.length < 1) {
            list.innerHTML = '';
            return;
        }
        timer = setTimeout(() => {
            fetch('{{ route('recipes.autocomplete') }}?query=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    if (data.length === 0) {
                        const noResults = document.createElement('div');
                        noResults.className = 'autocomplete-item';
                        noResults.textContent = 'Ничего не найдено';
                        list.appendChild(noResults);
                        return;
                    }
                    data.forEach((recipe, index) => {
                        const item = document.createElement('div');
                        item.className = 'autocomplete-item';
                        item.textContent = recipe.title;
                        item.onclick = () => window.location.href = `/recipes/${recipe.id}`;
                        list.appendChild(item);
                    });
                })
                .catch(error => {
                    console.error('Error fetching autocomplete results:', error);
                });
        }, 200);
    });

    input.addEventListener('keydown', function(e) {
        const items = list.getElementsByClassName('autocomplete-item');
        if (items.length === 0) return;

        if (e.key === 'ArrowDown') {
            currentFocus++;
            addActive(items);
            e.preventDefault();
        } else if (e.key === 'ArrowUp') {
            currentFocus--;
            addActive(items);
            e.preventDefault();
        } else if (e.key === 'Enter' && currentFocus > -1) {
            e.preventDefault();
            items[currentFocus].click();
        }
    });

    function addActive(items) {
        if (!items) return false;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (items.length - 1);
        items[currentFocus].classList.add('active');
    }

    function removeActive(items) {
        for (let i = 0; i < items.length; i++) {
            items[i].classList.remove('active');
        }
    }

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !list.contains(e.target)) {
            list.innerHTML = '';
            currentFocus = -1;
        }
    });
});
</script>
@endpush
