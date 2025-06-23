<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Favorite;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Просмотр профиля
    public function show()
    {
        $user = Auth::user();
        $recipes = $user->recipes()->latest()->paginate(5); // Получаем рецепты пользователя
        $favorites = $user->favorites()->get();

        return view('profile.show', compact('user', 'recipes', 'favorites'));
    }

    // Форма редактирования профиля
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Обновление профиля
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|confirmed|min:8',
        ]);

        // Проверка старого пароля
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Неверный старый пароль.']);
            }
        }

        // Обработка загрузки аватара
        if ($request->hasFile('avatar')) {
            // Удаляем старое изображение, если оно есть
            if ($user->avatar) {
                Storage::delete(str_replace('/storage/', 'public/', $user->avatar));
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        // Обновляем пароль, если он был указан
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        // Обновляем данные пользователя
        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Профиль успешно обновлен!');
    }

    // Удаление профиля (дополнительно)
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        Auth::logout();

        return redirect()->route('home')->with('success', 'Ваш аккаунт был успешно удален.');
    }

    // Форма смены пароля
    public function editPassword()
    {
        return view('profile.password');
    }

    // Обработка смены пароля
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Пароль успешно изменён!');
    }
}
