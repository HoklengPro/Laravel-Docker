<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects') }}
            </h2>
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Create New Project
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($projects->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($projects as $project)
                                <div class="border rounded-lg p-4 hover:shadow-lg transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-semibold">{{ $project->name }}</h3>
                                        <span class="px-2 py-1 text-xs rounded 
                                            @if($project->status === 'active') bg-green-100 text-green-800
                                            @elseif($project->status === 'completed') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($project->description, 100) }}</p>
                                    
                                    <div class="text-xs text-gray-500 mb-3">
                                        <p>Created by: {{ $project->creator->name ?? 'N/A' }}</p>
                                        <p>Tasks: {{ $project->tasks->count() }}</p>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                        
                                        @if(auth()->user()->hasRole('admin') || $project->created_by === auth()->id())
                                        <a href="{{ route('projects.edit', $project) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">Edit</a>
                                        
                                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">No projects found. 
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                                <a href="{{ route('projects.create') }}" class="text-blue-600 hover:underline">Create one now</a>
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
