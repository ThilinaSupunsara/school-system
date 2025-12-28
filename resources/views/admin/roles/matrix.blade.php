<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Role Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm rounded flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.permissions.update') }}">
                @csrf

                <div class="flex justify-end mb-6 sticky top-4 z-50">
                    <button type="submit" class="bg-blue-800 text-white px-6 py-3 rounded-full font-bold hover:bg-blue-900 shadow-lg flex items-center transform hover:scale-105 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Save All Changes
                    </button>
                </div>

                <div class="space-y-8">
                    @foreach($groupedPermissions as $groupName => $permissions)

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide flex items-center">
                                    <span class="bg-blue-100 text-blue-800 p-2 rounded-full mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </span>
                                    {{ $groupName }} Management
                                </h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Action</th>
                                            @foreach($roles as $role)
                                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                    {{ $role->name }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach($permissions as $permission)
                                            <tr class="hover:bg-blue-50 transition duration-150">

                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                                    @php
                                                        // "invoice.create" -> "Create" (Group name eka ain karanawa)
                                                        $parts = explode('.', $permission->name);
                                                        $action = isset($parts[1]) ? ucwords(str_replace('-', ' ', $parts[1])) : $permission->name;
                                                    @endphp
                                                    {{ $action }}
                                                </td>

                                                @foreach($roles as $role)
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <label class="inline-flex items-center cursor-pointer">
                                                            <input type="checkbox"
                                                                   name="permissions[{{ $role->id }}][]"
                                                                   value="{{ $permission->name }}"
                                                                   class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition duration-150 ease-in-out"
                                                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                        </label>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @endforeach
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-blue-800 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-900 shadow-lg">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
