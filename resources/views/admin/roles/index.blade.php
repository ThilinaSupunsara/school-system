<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage User Roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Role</h3>
                    <form method="POST" action="{{ route('admin.roles.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Role Name</label>
                            <input type="text" name="name" placeholder="e.g. librarian" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            <p class="text-xs text-gray-500 mt-1">Role name will be saved in lowercase.</p>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900">
                            Create Role
                        </button>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Existing Roles</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach ($roles as $role)
                            <li class="py-3 flex justify-between items-center">
                                <span class="capitalize font-semibold">{{ $role->name }}</span>

                                @if(!in_array($role->name, ['admin', 'accountant', 'teacher']))
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">System Core</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
