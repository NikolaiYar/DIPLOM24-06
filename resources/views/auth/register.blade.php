@extends('layouts.layout')

@section('title', 'Регистрация')

@section('content')
    <div class="container">
        <h1>Регистрация</h1>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="profile-form">
            @csrf

            <!-- Имя -->
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-control">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-control">
            </div>

            <!-- Пароль -->
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required class="form-control">
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Подтверждение пароля -->
            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control">
                @error('password_confirmation')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Аватар -->
            <div class="form-group">
                <label for="avatar">Аватар</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="form-control">
            </div>

            <button type="submit" class="submit-btn">Зарегистрироваться</button>
        </form>
    </div>
@endsection
