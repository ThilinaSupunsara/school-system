<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Student Invoices') }}
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

                    <form method="POST" action="{{ route('finance.invoices.generate.process') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="grade_id" class="block font-medium text-sm text-gray-700">{{ __('Grade') }}</label>
                            <select name="grade_id" id="grade_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                <option value="">{{ __('Select a Grade') }}</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="fee_category_id" class="block font-medium text-sm text-gray-700">{{ __('Fee Category') }}</label>
                            <select name="fee_category_id" id="fee_category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                <option value="">{{ __('Select a Fee Category') }}</option>
                                @foreach ($feeCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('fee_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="invoice_date" class="block font-medium text-sm text-gray-700">{{ __('Invoice Date') }}</label>
                                <input id="invoice_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="date"
                                       name="invoice_date"
                                       value="{{ old('invoice_date', now()->format('Y-m-d')) }}"
                                       required />
                            </div>

                            <div>
                                <label for="due_date" class="block font-medium text-sm text-gray-700">{{ __('Due Date') }}</label>
                                <input id="due_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="date"
                                       name="due_date"
                                       value="{{ old('due_date', now()->addDays(14)->format('Y-m-d')) }}"
                                       required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Generate Invoices') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
