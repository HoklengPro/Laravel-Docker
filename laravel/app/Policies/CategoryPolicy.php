<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function view(User $user, Category $category): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('manager')) return true;
        if ($user->hasRole('staff')) return true;
        return false;
    }

    public function updateStatus(User $user, Category $category): bool
    {
        return $user->hasRole('staff');
    }
}
