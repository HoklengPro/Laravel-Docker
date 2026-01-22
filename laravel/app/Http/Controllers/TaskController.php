<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            $tasks = Task::with('project', 'assignedUser')->get();
        } elseif ($user->hasRole('manager')) {
            $tasks = Task::whereHas('project', function($q) use ($user) {
                $q->where('created_by', $user->id);
            })->with('project', 'assignedUser')->get();
        } else {
            $tasks = Task::where('assigned_to', $user->id)->with('project', 'assignedUser')->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task
     */
    public function create()
    {
        abort_unless(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'), 403);
        
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            $projects = Project::all();
        } else {
            $projects = Project::where('created_by', $user->id)->get();
        }
        
        $staff = User::whereHas('roles', function($q) {
            $q->where('name', 'staff');
        })->get();

        return view('tasks.create', compact('projects', 'staff'));
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        abort_unless($request->user()->hasRole('admin') || $request->user()->hasRole('manager'), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task = Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    /**
     * Display the specified task
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        $task->load('project', 'assignedUser');
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(Task $task)
    {
        abort_unless(
            auth()->user()->hasRole('admin') || 
            ($task->project && $task->project->created_by === auth()->id()), 
            403
        );

        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            $projects = Project::all();
        } else {
            $projects = Project::where('created_by', $user->id)->get();
        }
        
        $staff = User::whereHas('roles', function($q) {
            $q->where('name', 'staff');
        })->get();

        return view('tasks.edit', compact('task', 'projects', 'staff'));
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, Task $task)
    {
        $user = $request->user();

        // Staff can only update status of their own tasks
        if ($user->hasRole('staff')) {
            $this->authorize('updateStatus', $task);
            
            $validated = $request->validate([
                'status' => 'required|in:pending,in_progress,completed',
            ]);
            
            $task->update($validated);
        } else {
            // Managers and admins can update everything
            abort_unless(
                $user->hasRole('admin') || 
                ($task->project && $task->project->created_by === $user->id), 
                403
            );

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'assigned_to' => 'nullable|exists:users,id',
                'status' => 'required|in:pending,in_progress,completed',
            ]);

            $task->update($validated);
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified task
     */
    public function destroy(Task $task)
    {
        abort_unless(
            auth()->user()->hasRole('admin') || 
            ($task->project && $task->project->created_by === auth()->id()), 
            403
        );

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }
}
