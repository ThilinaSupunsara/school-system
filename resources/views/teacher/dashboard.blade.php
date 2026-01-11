<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Teacher Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Welcome back, <strong>{{ Auth::user()->name }}</strong>. Ready to inspire today?</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm flex items-center">
                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-bold text-gray-700">{{ date('F d, Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <a href="{{ route('teacher.attendance.class_list') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Daily Task</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Mark Attendance</h3>
                    <p class="text-sm text-gray-500 mt-2">Record student attendance for your assigned classes.</p>
                </a>

                <a href="{{ route('teacher.payroll.index') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-green-200 transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-12 w-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Financial</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors">My Payslips</h3>
                    <p class="text-sm text-gray-500 mt-2">View and download your monthly salary history.</p>
                </a>

                <a href="{{ route('profile.edit') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-200 transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-12 w-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Settings</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">My Profile</h3>
                    <p class="text-sm text-gray-500 mt-2">Manage your account details and password.</p>
                </a>

            </div>

            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-blue-600 p-8 shadow-lg">
                <div class="relative z-10 text-white">
                    <h3 class="text-2xl font-bold">Have a wonderful day teaching!</h3>
                    <p class="mt-2 text-indigo-100 max-w-2xl text-lg">
                        "Education is the passport to the future, for tomorrow belongs to those who prepare for it today."
                    </p>
                </div>
                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl"></div>
            </div>

        </div>
    </div>
</x-app-layout>
