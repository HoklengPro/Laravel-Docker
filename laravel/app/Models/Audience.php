<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Audience extends Model
{
    protected $fillable = ['name', 'user_id'];

    // 2. An audience has one user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 4. An audience subscribed to many articles
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class)
            ->withTimestamps()
            ->withPivot('subscribed_at');
    }

    // 5. An audience have many comments (polymorphic)
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
