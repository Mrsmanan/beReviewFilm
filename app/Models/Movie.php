<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Movie extends Model
{
    use HasFactory, HasUuids;

    protected $table = "movies";
    protected $fillable = ['title', 'summary', 'poster', 'genre_id', 'year'];

    public function listGenres()
    {
        return $this->hasMany(Movie::class, 'genre_id');
    }
}