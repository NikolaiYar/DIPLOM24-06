<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Кулинарный сайт')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/recipes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
    @stack('styles')
    <style>
        html, body {
            height: 100%;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: #e8f4f8;
        }
        body {
            position: relative;
            overflow-x: hidden;
        }
        .content-wrapper {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <header class="header">
            <div class="container">
                <nav class="navbar">
                    <ul class="main-menu">
                        <li><a href="{{ route('home') }}">Главная</a></li>
                        <li><a href="{{ route('recipes.index') }}">Рецепты</a></li>
                        @auth
                            @if(auth()->user()->role_id === 1)
                                <li><a href="{{ route('categories.index') }}">Категории</a></li>
                                <li><a href="{{ route('ingredients.create') }}">Ингредиенты</a></li>
                            @endif
                            <li><a href="{{ route('profile') }}">Профиль</a></li>
                            <li class="logout-item">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Выйти</button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}">Войти</a></li>
                            <li><a href="{{ route('register') }}">Регистрация</a></li>
                        @endauth
                        <li>
                            <button id="theme-toggle" style="background:none;border:none;font-size:1.5rem;cursor:pointer;outline:none;" title="Переключить тему">
                                <span id="theme-icon">🌙</span>
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <main class="content">
            <div class="container">
                <div class="content-card">
                    @yield('content')
                </div>
            </div>
        </main>

        @php
            $isHome = request()->routeIs('home');
        @endphp

        @if ($isHome)
            <footer class="footer">
                <div class="container">
                    <p>&copy; {{ date('Y') }} Кулинарный сайт. Все права защищены.</p>
                </div>
            </footer>
        @endif
    </div>

    @stack('scripts')
    <script>
    (function() {
        const body = document.body;
        const btn = document.getElementById('theme-toggle');
        const icon = document.getElementById('theme-icon');
        if (!btn || !icon) return;
        // Проверяем localStorage
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-theme');
            icon.textContent = '☀️';
        }
        btn.addEventListener('click', function() {
            body.classList.toggle('dark-theme');
            const isDark = body.classList.contains('dark-theme');
            icon.textContent = isDark ? '☀️' : '🌙';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    })();
    </script>
</body>
</html>

