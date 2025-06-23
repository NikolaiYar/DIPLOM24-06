@extends('layouts.layout')

@section('title', 'Смена пароля')

@section('content')
    <div class="container">
        <div class="profile-edit-container">
            <h1 class="profile-edit-title">Смена пароля</h1>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('profile.password.update') }}" method="POST" class="profile-edit-form">
                @csrf
                <div class="form-group">
                    <label for="password">Новый пароль</label>
                    <input type="password" name="password" id="password" class="form-input" required minlength="8">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required minlength="8">
                </div>
                <button type="submit" class="btn btn-primary">Сменить пароль</button>
                <a href="{{ route('profile.edit') }}" class="btn btn-secondary" style="margin-left: 1rem;">Назад к профилю</a>
            </form>
        </div>
    </div>
@endsection 