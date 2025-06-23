@extends('layouts.layout')

@section('content')
    <div class="container my-5">
        <h1 class="profile-title">Ваш профиль</h1>
        <!-- Основная рамка для профиля -->
        <div class="profile-card">
            <div class="profile-content">
                <!-- Аватар -->
                <div class="profile-avatar-wrapper">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Аватар" class="profile-avatar">
                    @else
                        <img src="{{ asset('assets/images/default-avatar.jpg') }}" alt="Аватар" class="profile-avatar">
                    @endif
                </div>

                <!-- Информация о пользователе -->
                <div class="profile-info">
                    <h4 class="profile-name">{{ $user->name }}</h4>
                    <p class="text-muted mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="text-muted mb-1"><strong>Дата регистрации:</strong> {{ \Carbon\Carbon::parse($user->registration_date)->locale('ru')->translatedFormat('d F Y в H:i') }}</p>
                </div>
            </div>

            <!-- Раздел действий -->
            <div class="mt-4 d-flex justify-content-center">
                <a href="{{ route('profile.edit') }}" class="btn profile-edit-btn mx-2">
                    <i class="fas fa-user-edit"></i> Редактировать профиль
                </a>
                <form action="{{ route('profile.destroy') }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger profile-edit-btn mx-2">
                        <i class="fas fa-user-times"></i> Удалить аккаунт
                    </button>
                </form>
            </div>

            <!-- Секция избранных рецептов -->
            <div class="favorites-section mt-5">
                <h3 class="text-center mb-4">Ваши избранные рецепты</h3>
                @if ($favorites->isEmpty())
                    <p class="text-muted text-center">У вас нет избранных рецептов.</p>
                @else
                    <div class="favorites-list">
                        @foreach ($favorites as $favorite)
                            <div class="favorite-card">
                                <img src="{{ asset($favorite->image) }}" alt="{{ $favorite->title }}" class="favorite-image">
                                <div class="favorite-title">{{ $favorite->title }}</div>
                                <div class="favorite-desc">{{ Str::limit($favorite->description, 60) }}</div>
                                <div class="favorite-meta">
                                    <span>⏱ {{ $favorite->cook_time }} мин.</span>
                                    <span>⭐ {{ $favorite->ratings->avg('rating') ?? 'Нет оценок' }}</span>
                                </div>
                                <a href="{{ route('recipes.show', $favorite->id) }}" class="favorite-btn">Подробнее</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
