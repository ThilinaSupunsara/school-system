<x-app-layout>
    <x-slot name="header">{{ __('Issue New Cash Advance') }}</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <p class="font-bold">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('finance.expenses.store') }}" x-data="{ type: '{{ old('recipient_type', 'staff') }}' }">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 @error('category_id') border-red-500 @enderror" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Don't see the category? <a href="{{ route('finance.expense-categories.index') }}" class="text-blue-600 underline">Add New</a>
                        </p>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Description (Task) <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="description"
                               value="{{ old('description') }}"
                               class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('description') border-red-500 @enderror"
                               placeholder="e.g. Buying Paint"
                               required>

                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>



                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Amount to Give (LKR) <span class="text-red-500">*</span></label>
                        <input type="number"
                               step="0.01"
                               name="amount_given"
                               value="{{ old('amount_given') }}"
                               class="block mt-1 w-full rounded-md shadow-sm border-gray-300 @error('amount_given') border-red-500 @enderror"
                               required>
                        @error('amount_given')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Recipient Type <span class="text-red-500">*</span></label>
                        <select name="recipient_type" x-model="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            <option value="staff">Staff Member</option>
                            <option value="external">External Person</option>
                        </select>
                    </div>

                    <div class="mb-4" x-show="type === 'staff'">
                        <label class="block font-medium text-sm text-gray-700">Select Staff <span class="text-red-500">*</span></label>
                        <select name="staff_id"
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 @error('staff_id') border-red-500 @enderror"
                                :required="type === 'staff'">
                            <option value="">-- Choose Staff --</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" {{ old('staff_id') == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->user->name }} ({{ $staff->designation }})
                                </option>
                            @endforeach
                        </select>
                        @error('staff_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4" x-show="type === 'external'">
                        <label class="block font-medium text-sm text-gray-700">External Person Name <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="external_name"
                               value="{{ old('external_name') }}"
                               class="block mt-1 w-full rounded-md shadow-sm border-gray-300 @error('external_name') border-red-500 @enderror"
                               placeholder="e.g. Driver Kamal"
                               :required="type === 'external'">
                        @error('external_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-blue-800 text-white px-6 py-2 rounded-md hover:bg-blue-900 font-bold shadow-md">
                            Issue Cash
                        </button>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
