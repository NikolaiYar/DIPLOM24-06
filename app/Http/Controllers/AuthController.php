<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Вход в систему
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // перенаправляем на главную
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ]);
    }

    // Показать форму регистрации
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Регистрация пользователя
    public function register(Request $request)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Валидация для аватара
        ],
        [
            'password.min' => 'Поле Пароль должно содержать не менее :min символов.',
            'password.required' => 'Поле Пароль обязательно для заполнения.',
            'password.confirmed' => 'Поле Подтверждение пароля не совпадает.',
            'name.required' => 'Поле Имя обязательно для заполнения.',
            'name.max' => 'Поле Имя не должно превышать :max символов.',
            'email.required' => 'Поле Email обязательно для заполнения.',
            'email.email' => 'Поле Email должно быть действительным адресом электронной почты.',
            'email.max' => 'Поле Email не должно превышать :max символов.',
            'email.unique' => 'Пользователь с таким Email уже существует.',
            'avatar.image' => 'Файл Аватар должен быть изображением.',
            'avatar.mimes' => 'Изображение Аватар должно быть в формате JPEG, PNG, JPG или GIF.',
            'avatar.max' => 'Размер изображения Аватар не должен превышать 2 МБ.',
        ]);

        // Проверка, если email уже существует
        if (User::where('email', $validatedData['email'])->exists()) {
            return back()->with('error', 'Пользователь с таким email уже существует!');
        }

        // Проверка, если имя уже существует
        if (User::where('name', $validatedData['name'])->exists()) {
            return back()->with('error', 'Пользователь с таким именем уже существует!');
        }

        // Хешируем пароль
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Создание нового пользователя
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'], // Хешированный пароль
            'avatar' => $request->file('avatar') ? $request->file('avatar')->store('avatars', 'public') : null, // Сохранение аватара
            'role_id' => 2, // Роль по умолчанию
            'registration_date' => now(), // Дата регистрации
        ]);

        // Авторизация после регистрации
        Auth::login($user);

        // Редирект на главную страницу с успешным сообщением
        return redirect()->route('home')->with('success', 'Регистрация прошла успешно!');
    }

    // Выход из системы
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
