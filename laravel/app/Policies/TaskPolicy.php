<?php

namespace App\Policies;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('manager')) {
            return $task->products->created_by === $user->id;
        }
        if ($user->hasRole('staff')) {
            return $task->assigned_to === $user->id;
        }
        return false;
    }

    public function updateStatus(User $user, Task $task): bool
    {
        return $user->hasRole('staff') && $task->assigned_to === $user->id;
    }
}
