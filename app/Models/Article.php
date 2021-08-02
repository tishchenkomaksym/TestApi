<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'image', 'slug', 'author_id'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function state()
    {
        return $this->hasOne(State::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
