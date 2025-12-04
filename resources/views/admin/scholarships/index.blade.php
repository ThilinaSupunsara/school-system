<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Scholarship Types') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Scholarship</h3>
                    <form method="POST" action="{{ route('admin.scholarships.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Scholarship Name</label>
                            <input type="text" name="name" placeholder="e.g. Sports Scholarship" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Discount Amount (LKR)</label>
                            <input type="number" step="0.01" name="amount" placeholder="e.g. 1000.00" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            <p class="text-xs text-gray-500 mt-1">This amount will be deducted from the student's invoice.</p>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900">
                            Create
                        </button>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Existing Scholarships</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse ($scholarships as $scholarship)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-bold">{{ $scholarship->name }}</p>
                                    <p class="text-sm text-green-600">LKR {{ number_format($scholarship->amount, 2) }} Off</p>
                                </div>

                                <form method="POST" action="{{ route('admin.scholarships.destroy', $scholarship->id) }}" onsubmit="return confirm('Are you sure you want to delete this scholarship type?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-bold px-3 py-1 rounded hover:bg-red-50 transition">
                                        Delete
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="text-gray-500">No scholarship types created yet.</li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
