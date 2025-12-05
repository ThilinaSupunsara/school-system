<x-app-layout>
    <x-slot name="header">{{ __('Other Expenses / Petty Cash') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('finance.expenses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 mb-6">
                    Issue New Cash
                </a>
                <div class="mb-6 bg-gray-50 p-4 rounded-lg border">
                    <form method="GET" action="{{ route('finance.expenses.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Category</label>
                                <select name="category_id" class="w-full text-sm border-gray-300 rounded-md">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Status</label>
                                <select name="status" class="w-full text-sm border-gray-300 rounded-md">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">From Date</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">To Date</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="bg-gray-800 text-white px-3 py-2 rounded text-sm font-bold hover:bg-gray-700">Filter</button>
                                <a href="{{ route('finance.expenses.index') }}" class="bg-gray-300 text-gray-700 px-3 py-2 rounded text-sm font-bold hover:bg-gray-400">Clear</a>

                                <a href="{{ route('finance.expenses.print', request()->all()) }}" target="_blank" class="bg-blue-600 text-white px-3 py-2 rounded text-sm font-bold hover:bg-blue-700 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Print
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                @if(session('success')) <div class="mb-4 text-green-600 font-bold">{{ session('success') }}</div> @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Category</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">Issued By</th>
                            <th class="px-4 py-2 text-left">Recipient</th>
                            <th class="px-4 py-2 text-right">Given (LKR)</th>
                            <th class="px-4 py-2 text-right">Spent (LKR)</th>
                            <th class="px-4 py-2 text-center">Status</th>
                            <th class="px-4 py-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($expenses as $expense)
                            <tr>
                                <td class="px-4 py-3">{{ $expense->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">
                                    <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs">
                                        {{ $expense->category->name ?? 'None' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $expense->description }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    {{ $expense->issuer->name ?? 'System' }}
                                </td>
                                <td class="px-4 py-3">{{ $expense->recipient_name }}</td>
                                <td class="px-4 py-3 text-right font-bold">{{ number_format($expense->amount_given, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    {{ $expense->amount_spent ? number_format($expense->amount_spent, 2) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($expense->status == 'completed')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if($expense->status == 'pending')
                                        <a href="{{ route('finance.expenses.edit', $expense->id) }}" class="text-blue-600 hover:underline font-bold">Settle</a>
                                    @else
                                        @if($expense->receipt_path)
                                            <a href="{{ route('finance.expenses.view_receipt', $expense->id) }}" target="_blank" class="text-gray-600 hover:text-blue-600 hover:underline text-xs flex items-center justify-end">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                View Receipt
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">No Receipt</span>
                                        @endif
                                    @endif
                                    <form action="{{ route('finance.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this expense record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $expenses->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
