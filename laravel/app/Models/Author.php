<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Author extends Model
{
    protected $fillable = ['name', 'user_id'];

    // 1. An author has one user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 3. An author wrote multiple articles
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    // 7. An author have many comments (polymorphic)
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // 9. An author has many audiences (through articles)
    public function audiences(): HasManyThrough
    {
        return $this->hasManyThrough(
            Audience::class,      // Final model
            Article::class,       // Intermediate model
            'author_id',          // Foreign key on articles table
            'id',                 // Foreign key on audiences table
            'id',                 // Local key on authors table
            'id'                  // Local key on articles table
        )->distinct();
    }

    // Alternative: Get audiences through article subscriptions
    public function subscribers()
    {
        return $this->hasManyThrough(
            Audience::class,
            Article::class,
            'author_id',     // Foreign key on articles
            'id',            // Foreign key on audiences
            'id',            // Local key on authors
            'id'             // Local key on articles
        )
            ->join('article_audience', 'audiences.id', '=', 'article_audience.audience_id')
            ->where('article_audience.article_id', '=', \DB::raw('articles.id'))
            ->distinct();
    }
}
