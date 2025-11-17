<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accountant Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">Pending Invoices</h3>
                        <p class="text-3xl font-bold mt-2">{{ $pendingInvoicesCount }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">Total Due Amount</h3>
                        <p class="text-3xl font-bold mt-2 text-blue-600">
                            LKR {{ number_format($totalDueAmount, 2) }}
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">Total Overdue</h3>
                        <p class="text-3xl font-bold mt-2 text-red-600">
                            LKR {{ number_format($totalOverdueAmount, 2) }}
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">This Month's Income</h3>
                        <p class="text-3xl font-bold mt-2 text-green-600">
                            LKR {{ number_format($monthlyIncome, 2) }}
                        </p>
                    </div>
                </div>

            </div>
            </div>
    </div>
</x-app-layout>
