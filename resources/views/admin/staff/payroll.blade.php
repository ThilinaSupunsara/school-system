<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <button onclick="window.history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            Manage Payroll Components for: {{ $staff->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class...="p-4 bg-green-100 ...">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class...="p-4 bg-red-100 ...">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add Allowance</h3>
                        <form method="POST" action="{{ route('admin.staff.allowances.store', $staff->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="allowance_name" class="block font-medium text-sm text-gray-700">{{ __('Allowance Name') }}</label>
                                <input id="allowance_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="allowance_name" value="{{ old('allowance_name') }}" required />
                            </div>
                            <div class="mb-4">
                                <label for="allowance_amount" class="block font-medium text-sm text-gray-700">{{ __('Amount') }}</S</label>
                                <input id="allowance_amount" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="number" step="0.01" min="0" name="allowance_amount" value="{{ old('allowance_amount') }}" required />
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 ...">
                                {{ __('Add Allowance') }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Current Allowances</h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse ($staff->allowances as $allowance)
                                <li class="py-2 flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $allowance->name }}</span>:
                                        <span>LKR {{ number_format($allowance->amount, 2) }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.allowances.destroy', $allowance->id) }}" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type..." class="text-red-600 ...">Remove</button>
                                    </form>
                                </li>
                            @empty
                                <li class="py-2 text-gray-500">No allowances added yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add Deduction</h3>
                        <form method="POST" action="{{ route('admin.staff.deductions.store', $staff->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="deduction_name" class...="block ...">{{ __('Deduction Name') }}</label>
                                <input id="deduction_name" class...="block mt-1 w-full ..." type="text" name="deduction_name" value="{{ old('deduction_name') }}" required />
                            </div>
                            <div class="mb-4">
                                <label for="deduction_amount" class...="block ...">{{ __('Amount') }}</S</label>
                                <input id="deduction_amount" class...="block mt-1 w-full ..." type="number" step="0.01" min="0" name="deduction_amount" value="{{ old('deduction_amount') }}" required />
                            </div>
                            <button type="submit" class="inline-flex items-center ...">
                                {{ __('Add Deduction') }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Current Deductions</h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse ($staff->deductions as $deduction)
                                <li class="py-2 flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $deduction->name }}</span>:
                                        <span>LKR {{ number_format($deduction->amount, 2) }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.deductions.destroy', $deduction->id) }}" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 ...">Remove</button>
                                    </form>
                                </li>
                            @empty
                                <li class="py-2 text-gray-500">No deductions added yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
