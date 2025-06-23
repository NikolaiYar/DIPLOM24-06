<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewHistory extends Model
{
    protected $fillable = ['date', 'recipe_id', 'user_id'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

