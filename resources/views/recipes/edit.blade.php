@extends('layouts.layout')

@section('title', 'Редактировать рецепт')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center text-primary font-weight-bold">Редактировать рецепт</h1>

        {{-- Уведомление об успехе --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded shadow-sm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label font-weight-bold text-secondary" style="font-size: 1.1rem;">Название рецепта</label>
                <input type="text" name="title" id="title" value="{{ old('title', $recipe->title) }}" class="form-control" required>
                @error('title')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label font-weight-bold text-secondary" style="font-size: 1.1rem;">Описание рецепта</label>
                <textarea name="description" id="description" class="form-control" required>{{ old('description', $recipe->description) }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex-row-fields">
                <div>
                    <label for="cook_time" class="form-label font-weight-bold text-secondary" style="font-size: 1.1rem;">Время готовки (мин.)</label>
                    <input type="number" name="cook_time" id="cook_time" value="{{ old('cook_time', $recipe->cook_time) }}" class="form-control" required min="1">
                    @error('cook_time')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="calories" class="form-label font-weight-bold text-secondary" style="font-size: 1.1rem;">Калории (ккал)</label>
                    <input type="number" name="calories" id="calories" value="{{ old('calories', $recipe->calories) }}" class="form-control" required min="0">
                    @error('calories')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="difficulty" class="form-label font-weight-bold text-secondary" style="font-size: 1.1rem;">Сложность</label>
                <select name="difficulty" id="difficulty" class="form-control" required>
                    <option value="1" {{ old('difficulty', $recipe->difficulty) == 1 ? 'selected' : '' }}>Легко</option>
                    <option value="2" {{ old('difficulty', $recipe->difficulty) == 2 ? 'selected' : '' }}>Средне</option>
                    <option value="3" {{ old('difficulty', $recipe->difficulty) == 3 ? 'selected' : '' }}>Сложно</option>
                </select>
                @error('difficulty')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label font-weight-bold text-secondary" style="font-size: 1.1rem;">Ингредиенты</label>
                <div class="ingredient-add-row" style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1rem; position: relative;">
                    <div style="position: relative; width: 220px;">
                        <input type="text" id="ingredient-autocomplete" class="form-control" placeholder="Поиск ингредиента..." autocomplete="off" style="max-width: 220px;">
                        <div id="autocomplete-list" class="autocomplete-items" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 10; background: #fff; border: 1px solid #d6d9db; border-top: none; border-radius: 0 0 6px 6px; box-shadow: 0 2px 8px rgba(58,90,143,0.08); display: none;"></div>
                    </div>
                    <input type="number" id="ingredient-quantity" class="form-control" placeholder="Кол-во" min="1" step="1" style="max-width: 90px;">
                    <select id="ingredient-unit" class="form-control" style="max-width: 90px;">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->short_name }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-ingredient-btn" class="btn btn-primary">Добавить</button>
                </div>
                <table class="table table-bordered table-sm" id="selected-ingredients-table" style="background: #fff;">
                    <thead>
                        <tr>
                            <th>Ингредиент</th>
                            <th>Кол-во</th>
                            <th>Ед.</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- JS: динамически -->
                    </tbody>
                </table>
                <input type="hidden" name="ingredients_json" id="ingredients-json">
                @error('ingredients_json')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="categories" class="form-label font-weight-bold text-secondary" style="font-size: 1.1rem;">Категории</label>
                <select name="categories[]" id="categories" class="form-control" multiple data-choices-category>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $recipe->categories->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('categories')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="instructions" class="form-label font-weight-bold text-secondary" style="font-size: 1.1rem;">Инструкция по приготовлению</label>
                <small class="form-text text-muted mt-0">Напишите каждый шаг с новой строки (нажмите Enter после каждого шага).</small>
                <textarea name="instructions" id="instructions" class="form-control" required>{{ old('instructions', $recipe->instructions) }}</textarea>
                @error('instructions')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="form-label font-weight-bold text-secondary" style="font-size: 1.1rem;">Изображение</label>
                <input type="file" name="image" id="image" class="form-control">
                @error('image')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                @if($recipe->image)
                    <div class="mt-2">
                        <img src="{{ asset($recipe->image) }}" alt="Текущее изображение" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary w-100">Обновить рецепт</button>
        </form>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
.choices__inner {
    min-height: 48px;
    border-radius: 8px;
    border: 2px solid #d6d9db;
    font-size: 1rem;
}
.choices__list--multiple .choices__item {
    background: #3a5a8f;
    color: #fff;
    border-radius: 4px;
    margin-right: 4px;
    margin-bottom: 4px;
    padding: 4px 10px;
}
.container.mt-5 {
    max-width: 650px;
    margin: 10px auto 0 auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(58, 90, 143, 0.08);
    padding: 2rem 2rem 2rem 2rem;
}
form.bg-light {
    background: #f7f9fc !important;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(58, 90, 143, 0.04);
    padding: 2rem 2rem 2rem 2rem;
    max-width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
}
.form-label, label {
    font-weight: 600;
    color: #3a5a8f;
    margin-bottom: 0.3rem;
    display: block;
}
.form-control, select, textarea {
    border-radius: 6px !important;
    border: 1.5px solid #d6d9db !important;
    font-size: 1rem;
    margin-bottom: 0.2rem;
    background: #fff;
    width: 100%;
    box-sizing: border-box;
}
textarea.form-control {
    min-height: 90px;
    resize: vertical;
}
.btn-primary.w-100 {
    font-size: 1.1rem;
    padding: 1rem 0;
    border-radius: 6px;
    font-weight: 600;
    margin-top: 1.2rem;
}
input[type="file"].form-control {
    padding: 0.5rem;
    border-radius: 6px;
    background: #fff;
}
.flex-row-fields {
    display: flex;
    gap: 1.2rem;
}
.flex-row-fields > div {
    flex: 1;
}
h1.mb-4 {
    text-align: left;
    color: #222;
    font-size: 2rem;
    margin-bottom: 2rem !important;
}
.autocomplete-items {
    border: 1px solid #d6d9db;
    border-top: none;
    max-height: 180px;
    overflow-y: auto;
    background: #fff;
    position: absolute;
    width: 100%;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(58,90,143,0.08);
    border-radius: 0 0 6px 6px;
    display: none;
}
.autocomplete-item {
    padding: 8px 12px;
    cursor: pointer;
    transition: background 0.15s;
}
.autocomplete-item:hover, .autocomplete-item.active {
    background: #f0f4fa;
}

/* Styles for dark theme */
.dark-theme .choices__inner {
    background: var(--card-bg) !important;
    color: var(--text) !important;
    border-color: var(--border) !important;
}
.dark-theme .choices__list--multiple .choices__item {
    background: var(--primary) !important;
    color: #fff !important;
}
.dark-theme .choices__list--dropdown,
.dark-theme .choices__list[aria-expanded],
.dark-theme .choices__item--selectable {
    background: var(--card-bg) !important;
    color: var(--text) !important;
}
.dark-theme .choices__item--disabled {
    background: var(--card-bg) !important;
    color: #888 !important;
    opacity: 0.7;
}
.dark-theme .choices__list--dropdown .choices__item--selectable.is-highlighted,
.dark-theme .choices__list[aria-expanded] .choices__item--selectable.is-highlighted {
    background: var(--primary) !important;
    color: #fff !important;
}

/* Adjustments for dark theme on edit page */
.dark-theme .container.mt-5,
.dark-theme form.bg-light {
    background: var(--bg) !important; /* Use --bg for form background */
    color: var(--text) !important;
    border-color: var(--border) !important;
}

.dark-theme h1.mb-4 {
    color: var(--text) !important;
}

.dark-theme .form-label,
.dark-theme label {
    color: var(--primary) !important; /* Use --primary for labels in dark theme */
}

.dark-theme .form-control,
.dark-theme select,
.dark-theme textarea {
    background: var(--card-bg) !important;
    color: var(--text) !important; 
    border-color: var(--border) !important;
}

.dark-theme .autocomplete-items {
    background: var(--card-bg) !important;
    color: var(--text) !important;
    border-color: var(--border) !important;
}

.dark-theme .autocomplete-item:hover,
.dark-theme .autocomplete-item.active {
    background: var(--primary) !important;
    color: #fff !important;
}

.dark-theme .table,
.dark-theme .table td {
     background: var(--card-bg) !important;
     color: var(--text) !important;
     border-color: var(--border) !important;
}

.dark-theme .table th {
     color: var(--text) !important; 
     background: var(--card-bg) !important; 
     border-color: var(--border) !important; 
}
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
// Список всех ингредиентов для автокомплита
const allIngredients = @json($ingredients->map(fn($i) => ['id'=>$i->id, 'name'=>$i->name]));
const allUnits = @json($units->map(fn($u) => ['id'=>$u->id, 'short_name'=>$u->short_name]));
// Уже выбранные (для редактирования)
const initialSelected = @json($initialSelected);

let selectedIngredients = [...initialSelected];

function renderSelectedIngredients() {
    const tbody = document.querySelector('#selected-ingredients-table tbody');
    tbody.innerHTML = '';
    selectedIngredients.forEach((item, idx) => {
        const unit = allUnits.find(u => u.id == item.unit_id);
        tbody.innerHTML += `<tr>
            <td>${item.name}<input type="hidden" name="ingredients[${item.id}][checked]" value="1"></td>
            <td><input type="number" name="ingredients[${item.id}][quantity]" value="${item.quantity ?? ''}" class="form-control form-control-sm" min="0" step="0.01" style="max-width:80px;"></td>
            <td><select name="ingredients[${item.id}][unit_id]" class="form-control form-control-sm" style="max-width:80px;">
                ${allUnits.map(u => `<option value="${u.id}" ${u.id==item.unit_id?'selected':''}>${u.short_name}</option>`).join('')}
            </select></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeIngredient(${item.id})">Удалить</button></td>
        </tr>`;
    });
    document.getElementById('ingredients-json').value = JSON.stringify(selectedIngredients);
}

function removeIngredient(id) {
    selectedIngredients = selectedIngredients.filter(i => i.id != id);
    renderSelectedIngredients();
}

// --- Autocomplete ---
const input = document.getElementById('ingredient-autocomplete');
const list = document.getElementById('autocomplete-list');
let currentFocus = -1;
input.addEventListener('input', function() {
    const value = this.value.toLowerCase();
    list.innerHTML = '';
    if (!value) { list.style.display = 'none'; return; }
    const filtered = allIngredients.filter(i => i.name.toLowerCase().includes(value) && !selectedIngredients.some(sel => sel.id == i.id));
    if (filtered.length === 0) { list.style.display = 'none'; return; }
    filtered.forEach((item, idx) => {
        const div = document.createElement('div');
        div.className = 'autocomplete-item';
        div.textContent = item.name;
        div.addEventListener('mousedown', function(e) {
            input.value = item.name;
            list.innerHTML = '';
            list.style.display = 'none';
            input.focus();
        });
        list.appendChild(div);
    });
    list.style.display = 'block';
    currentFocus = -1;
});
input.addEventListener('keydown', function(e) {
    let items = list.querySelectorAll('.autocomplete-item');
    if (!items.length) return;
    if (e.key === 'ArrowDown') {
        currentFocus++;
        if (currentFocus >= items.length) currentFocus = 0;
        setActive(items, currentFocus);
        e.preventDefault();
    } else if (e.key === 'ArrowUp') {
        currentFocus--;
        if (currentFocus < 0) currentFocus = items.length - 1;
        setActive(items, currentFocus);
        e.preventDefault();
    } else if (e.key === 'Enter') {
        if (currentFocus > -1) {
            items[currentFocus].dispatchEvent(new Event('mousedown'));
            e.preventDefault();
        }
    }
});
function setActive(items, idx) {
    items.forEach(i => i.classList.remove('active'));
    if (items[idx]) items[idx].classList.add('active');
}
document.addEventListener('click', function(e) {
    if (!input.contains(e.target) && !list.contains(e.target)) {
        list.innerHTML = '';
        list.style.display = 'none';
    }
});

document.getElementById('add-ingredient-btn').addEventListener('click', function() {
    const name = document.getElementById('ingredient-autocomplete').value.trim();
    const quantityInput = document.getElementById('ingredient-quantity');
    const quantity = quantityInput.value;
    if (!quantity || isNaN(quantity) || parseInt(quantity) != Number(quantity) || Number(quantity) < 1) {
        alert('Введите целое число больше 0 для количества!');
        quantityInput.focus();
        return;
    }
    const found = allIngredients.find(i => i.name.toLowerCase() === name.toLowerCase());
    if (!found) {
        alert('Выберите ингредиент из списка!');
        return;
    }
    if (selectedIngredients.some(i => i.id == found.id)) {
        alert('Ингредиент уже добавлен!');
        return;
    }
    const unit_id = document.getElementById('ingredient-unit').value;
    selectedIngredients.push({
        id: found.id,
        name: found.name,
        quantity: quantity,
        unit_id: unit_id
    });
    renderSelectedIngredients();
    document.getElementById('ingredient-autocomplete').value = '';
    quantityInput.value = '';
    document.getElementById('ingredient-unit').value = allUnits[0].id;
    list.innerHTML = '';
    list.style.display = 'none';
});

// Инициализация
renderSelectedIngredients();

document.addEventListener('DOMContentLoaded', function() {
    // Ингредиенты: включение/отключение полей количества и единиц измерения
    document.querySelectorAll('.ingredient-item input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const item = this.closest('.ingredient-item');
            const quantityInput = item.querySelector('input[type="number"]');
            const unitSelect = item.querySelector('select');
            quantityInput.disabled = !this.checked;
            unitSelect.disabled = !this.checked;
            if (!this.checked) {
                quantityInput.value = '';
                unitSelect.value = '1';
            }
        });
    });
    // Поиск по ингредиентам
    const searchInput = document.getElementById('ingredient-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            document.querySelectorAll('.ingredient-item').forEach(function(item) {
                const label = item.querySelector('label').textContent.toLowerCase();
                item.style.display = label.includes(value) ? '' : 'none';
            });
        });
    }
    const categorySelect = document.getElementById('categories');
    if (categorySelect) {
        new Choices(categorySelect, {
            removeItemButton: true,
            searchResultLimit: 10,
            renderChoiceLimit: 10,
            searchChoices: true,
            placeholder: true,
            placeholderValue: 'Выберите категории',
            noResultsText: 'Нет совпадений',
            itemSelectText: 'Выбрать',
            shouldSort: false,
            searchFields: ['label', 'value'],
            searchFloor: 1,
            searchFn: function(items, searchValue) {
                searchValue = searchValue.trim().toLowerCase();
                if (!searchValue) return items;
                return items.filter(item => item.label.toLowerCase().startsWith(searchValue));
            },
        });
    }
});
</script>
@endpush
