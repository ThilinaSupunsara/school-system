<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Petty Cash & Expenses') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Track daily expenses and petty cash disbursements.</p>
            </div>

            @can('expense.create')
            <a href="{{ route('finance.expenses.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl shadow-lg shadow-gray-200 transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Issue Cash
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form method="GET" action="{{ route('finance.expenses.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Category</label>
                            <select name="category_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Status</label>
                            <select name="status" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                   class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                   class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                        </div>

                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row justify-end items-center gap-3 border-t border-gray-100 pt-4">
                        <a href="{{ route('finance.expenses.index') }}" class="w-full sm:w-auto px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 text-center transition">
                            Reset
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-md">
                            Filter
                        </button>

                        <a href="{{ route('finance.expenses.print', request()->all()) }}" target="_blank"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-sm font-bold text-red-600 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Print Report
                        </a>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Personnel</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Given</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Spent</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-blue-50/30 transition-colors duration-200 group">

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                        {{ $expense->created_at->format('Y-m-d') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900">{{ $expense->description }}</span>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full w-fit mt-1">
                                                {{ $expense->category->name ?? 'Uncategorized' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">To: {{ $expense->recipient_name }}</div>
                                        <div class="text-xs text-gray-500">By: {{ $expense->issuer->name ?? 'System' }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-bold text-gray-900">LKR {{ number_format($expense->amount_given, 2) }}</span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($expense->amount_spent)
                                            <span class="text-sm font-medium text-gray-600">LKR {{ number_format($expense->amount_spent, 2) }}</span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($expense->status == 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span> Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span> Pending
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-3">
                                            @can('expense.edit')
                                                @if($expense->status == 'pending')
                                                    <a href="{{ route('finance.expenses.edit', $expense->id) }}"
                                                       class="text-blue-600 hover:text-blue-900 font-bold hover:underline transition">
                                                        Settle
                                                    </a>
                                                @elseif($expense->receipt_path)
                                                    <a href="{{ route('finance.expenses.view_receipt', $expense->id) }}" target="_blank"
                                                       class="text-gray-500 hover:text-blue-600 transition" title="View Receipt">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </a>
                                                @endif
                                            @endcan

                                            @can('expense.delete')
                                                <form class="inline-block" action="{{ route('finance.expenses.destroy', $expense->id) }}" method="POST" >

                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="confirmDelete(event)" class="text-red-400 hover:text-red-600 p-1 transition" title="Delete">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            <p class="text-base font-medium text-gray-900">No expense records found</p>
                                            <p class="text-sm text-gray-500">Adjust filters or issue new cash.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $expenses->links() }}
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
