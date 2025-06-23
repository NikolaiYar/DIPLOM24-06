<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $recipe = Recipe::first();
        $user = User::first();

        Comment::create([
            'text' => 'Этот рецепт просто великолепен!',
            'recipe_id' => $recipe->id,
            'user_id' => $user->id,
        ]);
    }
}
