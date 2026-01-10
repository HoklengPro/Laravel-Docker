<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    protected $fillable = ['content', 'user_id', 'commentable_id', 'commentable_type'];

    // 8. A comment belongs to a user (who wrote it)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic: A comment can be on Author, Article, or Audience
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
