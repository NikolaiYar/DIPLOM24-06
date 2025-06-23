@extends('layouts.layout')

@section('title', 'Вход')

@section('content')
    <div class="container">
        <h1>Вход в систему</h1>

        <form method="POST" action="{{ route('login') }}" class="profile-form">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" required class="form-control">
            </div>

            @error('email')
            <p class="error-message">{{ $message }}</p>
            @enderror

            <button type="submit" class="submit-btn">Войти</button>
        </form>
    </div>
@endsection
