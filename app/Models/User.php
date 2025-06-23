<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'password', 'name', 'email', 'registration_date', 'avatar', 'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Recipe::class, 'favorites', 'user_id', 'recipe_id');
    }

    public function viewHistories()
    {
        return $this->hasMany(ViewHistory::class);
    }
}
