<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table = 'favorites';

    protected $fillable = ['user_id', 'recipe_id'];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с рецептом
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
