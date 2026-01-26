<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">

            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Manage User Roles') }}
                </h2>
                <p class="text-sm text-gray-500">Define access levels and responsibilities.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="flex items-center p-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center p-4 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" ></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6 h-fit sticky top-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </span>
                            Create New Role
                        </h3>
                        <p class="text-sm text-gray-500 mt-2">Add a new role to the system (e.g. Librarian, Sports Coach).</p>
                    </div>

                    <form method="POST" action="{{ route('finance.roles.store') }}">
                        @csrf
                        <div class="space-y-5">

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Role Name</label>
                                <input type="text" name="name" placeholder="e.g. librarian" required autofocus
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                <p class="text-xs text-gray-400 mt-1 pl-1">Will be saved as lowercase (e.g. 'librarian').</p>
                            </div>

                            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-200">
                                Save Role
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-fit">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900">System Roles</h3>
                        <span class="text-xs font-bold bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $roles->count() }} Total</span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach ($roles as $role)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors group">
                                <div class="flex items-center">
                                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 14l-1 1-1 1H6v2H2v-2v-2.586l.293-.293l-1-1F15 7z"></path></svg>
                                    </div>
                                    <span class="capitalize font-bold text-gray-700">{{ $role->name }}</span>
                                </div>

                                @if(in_array($role->name, ['admin', 'accountant', 'teacher', 'super-admin']))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">
                                        System Core
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('finance.roles.destroy', $role->id) }}">

                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="confirmDelete(event)" class="text-gray-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Delete Role">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
     function confirmDelete(event) {

        event.preventDefault();


        const form = event.target.closest('form');


        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
</x-app-layout>
