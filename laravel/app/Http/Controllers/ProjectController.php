<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            $projects = Project::with('creator', 'tasks')->get();
        } elseif ($user->hasRole('manager')) {
            $projects = Project::where('created_by', $user->id)->with('creator', 'tasks')->get();
        } else {
            // Staff can see projects they have tasks in
            $projects = Project::whereHas('tasks', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            })->with('creator', 'tasks')->get();
        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        abort_unless(auth()->user()->can('products.create'), 403, 'Only managers and admins can create projects');
        
        return view('projects.create');
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        abort_unless($request->user()->can('products.create'), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on_hold',
        ]);

        $validated['created_by'] = $request->user()->id;

        $project = Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $project->load('creator', 'tasks.assignedUser');
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit(Project $project)
    {
        // Only admin or the creator can edit
        abort_unless(
            auth()->user()->hasRole('admin') || $project->created_by === auth()->id(), 
            403
        );

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        abort_unless(
            auth()->user()->hasRole('admin') || $project->created_by === auth()->id(), 
            403
        );

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on_hold',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        abort_unless(
            auth()->user()->hasRole('admin') || $project->created_by === auth()->id(), 
            403
        );

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
}
