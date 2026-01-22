<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">
                        Your Role(s): 
                        <span class="font-semibold text-blue-600">
                            {{ $user->roles->pluck('name')->map(fn($role) => ucfirst($role))->implode(', ') }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Quick Links based on Role -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                
                @if($user->hasRole('admin') || $user->hasRole('manager'))
                <!-- Projects Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold mb-2">Projects</h4>
                        <p class="text-gray-600 mb-4">Manage your projects</p>
                        <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            View Projects
                        </a>
                    </div>
                </div>
                @endif

                <!-- Tasks Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold mb-2">Tasks</h4>
                        <p class="text-gray-600 mb-4">
                            @if($user->hasRole('staff'))
                                View and update your assigned tasks
                            @else
                                Manage tasks
                            @endif
                        </p>
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            View Tasks
                        </a>
                    </div>
                </div>

                @if($user->hasRole('admin'))
                <!-- Users Management (Admin Only) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold mb-2">User Management</h4>
                        <p class="text-gray-600 mb-4">Manage users and roles</p>
                        <div class="text-sm text-gray-500">Coming soon...</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Role-specific Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4">Your Permissions</h4>
                    
                    @if($user->hasRole('admin'))
                        <div class="bg-red-50 border border-red-200 rounded p-4">
                            <h5 class="font-semibold text-red-800 mb-2">Administrator Access</h5>
                            <p class="text-red-700">You have full access to all features and can manage everything in the system.</p>
                        </div>
                    @elseif($user->hasRole('manager'))
                        <div class="bg-blue-50 border border-blue-200 rounded p-4">
                            <h5 class="font-semibold text-blue-800 mb-2">Manager Access</h5>
                            <ul class="list-disc list-inside text-blue-700 space-y-1">
                                <li>Create and manage your own projects</li>
                                <li>Create and assign tasks to staff members</li>
                                <li>View and update task status</li>
                                <li>Manage products and categories</li>
                            </ul>
                        </div>
                    @elseif($user->hasRole('staff'))
                        <div class="bg-green-50 border border-green-200 rounded p-4">
                            <h5 class="font-semibold text-green-800 mb-2">Staff Access</h5>
                            <ul class="list-disc list-inside text-green-700 space-y-1">
                                <li>View tasks assigned to you</li>
                                <li>Update status of your assigned tasks</li>
                                <li>View project details related to your tasks</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- API Information -->
            @if($user->hasRole('admin') || $user->hasRole('manager'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4">API Access</h4>
                    <p class="text-gray-600 mb-2">You can access the API using Laravel Passport tokens.</p>
                    <div class="bg-gray-100 p-4 rounded mt-2">
                        <p class="text-sm text-gray-700 mb-2"><strong>Login Endpoint:</strong> POST /api/login</p>
                        <p class="text-sm text-gray-700 mb-2"><strong>Your credentials:</strong> {{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-500">Use your login credentials to obtain an access token.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
