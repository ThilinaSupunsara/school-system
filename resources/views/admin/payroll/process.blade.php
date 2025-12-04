<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <button onclick="window.history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            {{ __('Process Staff Payroll') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('finance.payroll.process') }}">
                        @csrf

                        <p class="mb-4 text-gray-600">Select the month and year to process payroll for all staff members. This will calculate net salary based on their current basic salary, allowances, and deductions.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="mb-4">
                                <label for="month" class="block font-medium text-sm text-gray-700">{{ __('Month') }}</label>
                                <select name="month" id="month" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ old('month', $currentMonth) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="year" class="block font-medium text-sm text-gray-700">{{ __('Year') }}</label>
                                <input id="year" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="number"
                                       name="year"
                                       value="{{ old('year', $currentYear) }}"
                                       required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                                    onsubmit="return confirm('Are you sure you want to process payroll for this month? This cannot be undone easily.');">
                                {{ __('Process Payroll') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
