<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function destroy(Comment $comment)
    {
        $user = Auth::user();
        // Админ может удалять любые, пользователь — только свои
        if ($user->role_id === 1 || $comment->user_id === $user->id) {
            $comment->delete();
            return back()->with('success', 'Комментарий удалён!');
        }
        abort(403, 'Нет прав для удаления этого комментария.');
    }

    public function update(Request $request, Comment $comment)
    {
        $user = Auth::user();
        // Админ может редактировать любые, пользователь — только свои
        if ($user->role_id !== 1 && $comment->user_id !== $user->id) {
            abort(403, 'Нет прав для редактирования этого комментария.');
        }

        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment->text = $request->input('text');
        $comment->save();

        return back()->with('success', 'Комментарий успешно обновлён!');
    }
} 