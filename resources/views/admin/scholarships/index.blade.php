<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">

            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Scholarship Types') }}
                </h2>
                <p class="text-sm text-gray-500">Manage scholarship categories and discount amounts.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="flex items-center p-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6 h-fit sticky top-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </span>
                            Create New Scholarship
                        </h3>
                        <p class="text-sm text-gray-500 mt-2">Define a new scholarship type to be assigned to students.</p>
                    </div>

                    <form method="POST" action="{{ route('finance.scholarships.store') }}">
                        @csrf
                        <div class="space-y-5">

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Scholarship Name</label>
                                <input type="text" name="name" placeholder="e.g. Sports Scholarship" required autofocus
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Discount Amount (LKR)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="amount" placeholder="0.00" required
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors font-mono pl-3">
                                </div>
                                <p class="text-xs text-gray-400 mt-1">This amount will be deducted from invoices.</p>
                            </div>

                            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-200">
                                Save Scholarship
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-fit">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900">Existing Scholarships</h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($scholarships as $scholarship)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors group">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $scholarship->name }}</h4>
                                    <div class="flex items-center mt-1">
                                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded border border-green-100">
                                            LKR {{ number_format($scholarship->amount, 2) }} Off
                                        </span>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('finance.scholarships.destroy', $scholarship->id) }}">

                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="confirmDelete(event)" class="text-gray-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">No scholarships found</p>
                                <p class="text-xs text-gray-500 mt-1">Create one to get started.</p>
                            </div>
                        @endforelse
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
