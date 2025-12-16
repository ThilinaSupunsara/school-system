<x-app-layout>
    <x-slot name="header">{{ __('Manage Role Permissions') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded border-l-4 border-green-500">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.permissions.update') }}">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-3 text-left w-1/3">Permission Name</th>
                                    @foreach($roles as $role)
                                        <th class="border p-3 text-center uppercase">{{ $role->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($permissions as $permission)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="border p-3 font-medium text-gray-700">
                                            {{ $permission->name }}
                                        </td>

                                        @foreach($roles as $role)
                                            <td class="border p-3 text-center">
                                                <input type="checkbox"
                                                       name="permissions[{{ $role->id }}][]"
                                                       value="{{ $permission->name }}"
                                                       class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer"
                                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-800 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-900 shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
