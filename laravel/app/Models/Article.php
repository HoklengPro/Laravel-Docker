<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'author_id'];

    // 3. An article belongs to an author
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    // 4. An article have many audiences (subscribers)
    public function audiences(): BelongsToMany
    {
        return $this->belongsToMany(Audience::class)
            ->withTimestamps()
            ->withPivot('subscribed_at');
    }

    // 6. An article have many comments (polymorphic)
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
