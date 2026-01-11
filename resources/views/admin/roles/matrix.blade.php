<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Role Permissions') }}
                </h2>
                <p class="text-sm text-gray-500">Configure access levels for each user role.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-xl flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.permissions.update') }}">
                @csrf

                <div class="fixed bottom-6 right-6 z-50">
                    <button type="submit" class="bg-blue-800 text-white px-6 py-4 rounded-full font-bold hover:bg-blue-900 shadow-2xl flex items-center transform hover:scale-105 transition-all duration-200 border-2 border-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Save All Changes
                    </button>
                </div>

                <div class="space-y-8">
                    @foreach($groupedPermissions as $groupName => $permissions)

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center">
                                <span class="bg-white text-blue-600 p-2 rounded-lg shadow-sm mr-3 border border-gray-100">
                                    @if($groupName == 'Student') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    @elseif($groupName == 'Invoice') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    @elseif($groupName == 'Expense') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    @else <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    @endif
                                </span>
                                <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">
                                    {{ $groupName }} Management
                                </h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50/50">
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
                                            <tr class="hover:bg-blue-50/30 transition duration-150 group">

                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 group-hover:text-blue-700">
                                                    @php
                                                        $parts = explode('.', $permission->name);
                                                        $action = isset($parts[1]) ? ucwords(str_replace('-', ' ', $parts[1])) : $permission->name;
                                                    @endphp
                                                    {{ $action }}
                                                </td>

                                                @foreach($roles as $role)
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <label class="inline-flex items-center justify-center cursor-pointer w-full h-full">
                                                            <input type="checkbox"
                                                                   name="permissions[{{ $role->id }}][]"
                                                                   value="{{ $permission->name }}"
                                                                   class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer shadow-sm transition-transform transform hover:scale-110 active:scale-95"
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

                <div class="h-24"></div>

            </form>

        </div>
    </div>
</x-app-layout>
