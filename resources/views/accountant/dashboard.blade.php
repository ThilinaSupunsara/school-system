<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Financial Overview') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Welcome back, <strong>{{ Auth::user()->name }}</strong>. Here is today's financial summary.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm flex items-center">
                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-bold text-gray-700">{{ date('F d, Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending Invoices</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $pendingInvoicesCount }}</p>
                        <p class="text-xs text-yellow-600 font-medium mt-1 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Awaiting Payment
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-yellow-50 rounded-full flex items-center justify-center text-yellow-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Receivables</p>
                        <p class="text-2xl font-extrabold text-blue-600 mt-1">
                            LKR {{ number_format($totalDueAmount, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 font-medium mt-1">Total pending balance</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Overdue Amount</p>
                        <p class="text-2xl font-extrabold text-red-600 mt-1">
                            LKR {{ number_format($totalOverdueAmount, 2) }}
                        </p>
                        <p class="text-xs text-red-500 font-medium mt-1 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Action Required
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-red-50 rounded-full flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Income (This Month)</p>
                        <p class="text-2xl font-extrabold text-green-600 mt-1">
                            LKR {{ number_format($monthlyIncome, 2) }}
                        </p>
                        <p class="text-xs text-green-600 font-medium mt-1 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            Cash Inflow
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-green-50 rounded-full flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900">Accounting Tasks</h3>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4">

                    <a href="{{ route('finance.students.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50 transition-all group">
                        <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-2 group-hover:bg-blue-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Add Student</span>
                    </a>

                    <a href="{{ route('finance.staff.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-dashed border-gray-300 hover:border-green-400 hover:bg-green-50 transition-all group">
                        <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 mb-2 group-hover:bg-green-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Add Staff</span>
                    </a>

                    <a href="{{ route('finance.invoices.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-dashed border-gray-300 hover:border-yellow-400 hover:bg-yellow-50 transition-all group">
                        <div class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 mb-2 group-hover:bg-yellow-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Invoices & Fees</span>
                    </a>

                    <a href="{{ route('finance.payroll.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-dashed border-gray-300 hover:border-purple-400 hover:bg-purple-50 transition-all group">
                        <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mb-2 group-hover:bg-purple-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Payroll</span>
                    </a>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
