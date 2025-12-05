<x-app-layout>
    <x-slot name="header">{{ __('Settle Expense') }}: {{ $expense->description }}</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 p-4 bg-gray-100 rounded">
                    <p><strong>Given To:</strong> {{ $expense->recipient_name }}</p>
                    <p><strong>Amount Given:</strong> LKR {{ number_format($expense->amount_given, 2) }}</p>
                </div>

                <form method="POST" action="{{ route('finance.expenses.update', $expense->id) }}" enctype="multipart/form-data"
                      x-data="{ given: {{ $expense->amount_given }}, spent: {{ $expense->amount_given }} }">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Actual Amount Spent (LKR)</label>
                        <input type="number" step="0.01" name="amount_spent" x-model="spent" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                    </div>

                    <div class="mb-4 p-3 rounded"
                         :class="(given - spent) >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                        <p x-show="(given - spent) > 0">
                            <strong>Collect Balance:</strong> LKR <span x-text="(given - spent).toFixed(2)"></span>
                        </p>
                        <p x-show="(given - spent) < 0">
                            <strong>Reimburse (Pay Extra):</strong> LKR <span x-text="(spent - given).toFixed(2)"></span>
                        </p>
                        <p x-show="(given - spent) == 0">No balance due.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Upload Receipt (Optional)</label>
                        <input type="file" name="receipt" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-800">
                        Finalize & Settle
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
