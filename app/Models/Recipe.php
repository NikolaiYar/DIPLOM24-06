<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'title', 'description', 'cook_time', 'difficulty', 'calories', 'instructions', 'image', 'user_id'
    ];

    public function getDifficultyTextAttribute()
    {
        switch ($this->difficulty) {
            case 1:
                return 'Easy';
            case 2:
                return 'Medium';
            case 3:
                return 'Hard';
            default:
                return 'Unknown';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_recipe');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->using(RecipeIngredient::class)
            ->withPivot(['quantity', 'unit_id']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'recipe_id', 'user_id');
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function viewHistories()
    {
        return $this->hasMany(ViewHistory::class);
    }
}

