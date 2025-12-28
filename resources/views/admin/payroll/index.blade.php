<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Payroll Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @can('payroll.create')
                    <div class="mb-6">
                        <a href="{{ route('finance.payroll.process.form') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Process New Payroll') }}
                        </a>
                    </div>
                    @endcan

                    <form method="GET" action="{{ route('finance.payroll.index') }}" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Staff Name</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Month</label>
                                <select name="month" class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                                    <option value="">All Months</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Year</label>
                                <input type="number" name="year" value="{{ request('year') }}" placeholder="e.g. 2025" class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                                <select name="status" class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                                    <option value="">All Statuses</option>
                                    <option value="generated" {{ request('status') == 'generated' ? 'selected' : '' }}>Generated</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>

                        </div>

                        <div class="mt-4 flex justify-end space-x-2">
                            <a href="{{ route('finance.payroll.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-400 font-bold">
                                Reset
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 font-bold">
                                Filter
                            </button>
                            <a href="{{ route('finance.payroll.export_pdf', request()->all()) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 font-bold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                PDF
                            </a>
                        </div>
                    </form>
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month/Year</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Salary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($payrolls as $payroll)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payroll->staff->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} / {{ $payroll->year }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold">LKR {{ number_format($payroll->net_salary, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($payroll->status == 'paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Paid
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Generated
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('payroll.edit')
                                            <a href="{{ route('finance.payroll.show', $payroll->id) }}" class="text-blue-600 hover:text-blue-900 font-bold mr-3">
                                                Manage / Pay
                                            </a>
                                            @endcan

                                            @can('payroll.delete')
                                            <form class="inline" method="POST" action="{{ route('finance.payroll.destroy', $payroll->id) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this payroll record?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">
                                                    Delete
                                                </button>
                                            </form>
                                            @endcan
                                            
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No payroll records found matching your criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $payrolls->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
