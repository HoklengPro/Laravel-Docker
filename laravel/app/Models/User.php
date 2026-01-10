<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========================================
    // TP6: RBAC Relationships & Methods
    // ========================================

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', fn($q) => $q->where('name', $permission))
            ->exists();
    }

    /**
     * Get the tasks assigned to the user (if Task model exists).
     * Note: This is for TP6 if you have Task model
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // ========================================
    // TP7: Advanced Eloquent Relationships
    // ========================================

    /**
     * TP7 Relationship 1: User can be an Author
     * One-to-One relationship
     */
    public function author(): HasOne
    {
        return $this->hasOne(Author::class);
    }

    /**
     * TP7 Relationship 2: User can be an Audience
     * One-to-One relationship
     */
    public function audience(): HasOne
    {
        return $this->hasOne(Audience::class);
    }

    /**
     * TP7 Relationship 8: A user wrote many comments
     * One-to-Many relationship
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
