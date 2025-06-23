<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['rating', 'recipe_id', 'user_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rating) {
            if ($rating->rating < 1 || $rating->rating > 5) {
                throw new \Exception('Rating must be between 1 and 5.');
            }
        });
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
