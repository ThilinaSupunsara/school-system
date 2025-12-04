<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <button onclick="window.history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            {{ __('Edit Fee Structure') }}
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

                    <form method="POST" action="{{ route('finance.fee-structures.update', $feeStructure->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="grade_id" class="block font-medium text-sm text-gray-700">{{ __('Grade') }}</label>
                            <select name="grade_id" id="grade_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                <option value="">{{ __('Select a Grade') }}</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade_id', $feeStructure->grade_id) == $grade->id ? 'selected' : '' }}>
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
                                    <option value="{{ $category->id }}" {{ old('fee_category_id', $feeStructure->fee_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="amount" class="block font-medium text-sm text-gray-700">{{ __('Amount (LKR)') }}</label>
                            <input id="amount" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   name="amount"
                                   value="{{ old('amount', $feeStructure->amount) }}"
                                   required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 ...">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
