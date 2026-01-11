<div class="flex flex-col h-screen w-full min-w-[18rem] bg-white border-r border-gray-100 font-sans">

    <div class="flex-shrink-0 flex flex-col items-center justify-center py-8 border-b border-gray-100 bg-white z-10">
        @if(isset($schoolSettings) && $schoolSettings->logo_path)
            <div class="h-20 w-20 rounded-2xl bg-white shadow-lg shadow-blue-50 border border-gray-100 flex items-center justify-center overflow-hidden p-1.5 transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('storage/' . $schoolSettings->logo_path) }}"
                     alt="Logo"
                     class="h-full w-full object-contain rounded-xl">
            </div>
            <div class="mt-4 text-center px-4">
                <h1 class="text-sm font-extrabold text-gray-800 uppercase tracking-wider leading-tight">
                    {{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}
                </h1>
            </div>
        @else
            <div class="h-16 w-16 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-200 text-white font-bold text-2xl">
                {{ substr($schoolSettings->school_name ?? 'S', 0, 1) }}
            </div>
            <span class="mt-3 text-lg font-bold text-gray-800 tracking-tight">
                {{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}
            </span>
        @endif
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">

        <a href="{{ route('dashboard') }}"
           class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 ease-in-out mb-4
           {{ request()->routeIs('dashboard')
              ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100'
              : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">

            <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="mx-3">Dashboard</span>
        </a>

        @if(Auth::user()->role === 'teacher')
            <div class="mt-8 mb-2 px-4">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">My Tools</p>
            </div>

            <a href="{{ route('teacher.attendance.class_list') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out mb-1
               {{ request()->routeIs('teacher.attendance.*') ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('teacher.attendance.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="mx-3">Mark Attendance</span>
            </a>

            <a href="{{ route('teacher.payroll.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out mb-1
               {{ request()->routeIs('teacher.payroll.*') ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('teacher.payroll.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m4 2h.01M17 16l4-4M10 20h4a2 2 0 002-2v-3a2 2 0 00-2-2h-4a2 2 0 00-2 2v3a2 2 0 002 2z"/>
                </svg>
                <span class="mx-3">My Payslips</span>
            </a>
        @endif


        @if(in_array(Auth::user()->role, ['admin', 'accountant']))

            <div class="mt-8 mb-2 px-4">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Academic & Staff</p>
            </div>

            @can('student.view')
            <a href="{{ route('finance.students.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out mb-1
               {{ request()->routeIs('finance.students.*') ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('finance.students.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="mx-3">Students</span>
            </a>
            @endcan

            @can('staff.view')
            <a href="{{ route('finance.staff.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out mb-1
               {{ request()->routeIs('finance.staff.*') ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('finance.staff.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="mx-3">Staff Directory</span>
            </a>
            @endcan

            <details class="group {{ request()->routeIs(['finance.grades.*', 'finance.sections.*']) ? 'open' : '' }} mb-1">
                <summary class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl cursor-pointer transition-colors duration-200 hover:bg-gray-50 hover:text-gray-900
                    {{ request()->routeIs(['finance.grades.*', 'finance.sections.*']) ? 'bg-gray-100 text-gray-900' : '' }} list-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span class="mx-3 flex-1">Classes & Grades</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-300 group-open:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </summary>
                <div class="mt-1 space-y-1 ml-4 pl-4 border-l-2 border-gray-100">
                    <a href="{{ route('finance.grades.index') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.grades.*') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Grades Management
                    </a>
                    <a href="{{ route('finance.sections.index') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.sections.*') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Sections (Classes)
                    </a>
                </div>
            </details>

            <div class="mt-8 mb-2 px-4">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Finance</p>
            </div>

            @can('invoice.view')
            <a href="{{ route('finance.invoices.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out mb-1
               {{ request()->routeIs('finance.invoices.*') ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('finance.invoices.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M16 16v-3l-2.5-2.5L11 13V7m7 4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="mx-3">Invoices & Fees</span>
            </a>
            @endcan

            @can('payroll.view')
            <a href="{{ route('finance.payroll.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out mb-1
               {{ request()->routeIs('finance.payroll.*') ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('finance.payroll.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8a4 4 0 100-8 4 4 0 000 8zm-8 4h16a2 2 0 002-2v-6a2 2 0 00-2-2H4a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
                <span class="mx-3">Payroll</span>
            </a>
            @endcan

            <details class="group {{ request()->routeIs(['finance.expenses.*', 'finance.expense-categories.*']) ? 'open' : '' }} mb-1">
                <summary class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl cursor-pointer transition-colors duration-200 hover:bg-gray-50 hover:text-gray-900
                    {{ request()->routeIs(['finance.expenses.*', 'finance.expense-categories.*']) ? 'bg-gray-100 text-gray-900' : '' }} list-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m4 2h.01M17 16l4-4M10 20h4a2 2 0 002-2v-3a2 2 0 00-2-2h-4a2 2 0 00-2 2v3a2 2 0 002 2z"/></svg>
                    <span class="mx-3 flex-1">Expenses</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-300 group-open:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </summary>
                <div class="mt-1 space-y-1 ml-4 pl-4 border-l-2 border-gray-100">
                    @can('expense.view')
                    <a href="{{ route('finance.expenses.index') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.expenses.*') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Other Expenses
                    </a>
                    @endcan
                    <a href="{{ route('finance.expense-categories.index') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.expense-categories.*') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Expense Categories
                    </a>
                </div>
            </details>

            <details class="group {{ request()->routeIs(['finance.fee-structures.*', 'finance.fee-categories.*', 'finance.scholarships.*']) ? 'open' : '' }} mb-1">
                <summary class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl cursor-pointer transition-colors duration-200 hover:bg-gray-50 hover:text-gray-900
                    {{ request()->routeIs(['finance.fee-structures.*', 'finance.fee-categories.*', 'finance.scholarships.*']) ? 'bg-gray-100 text-gray-900' : '' }} list-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="mx-3 flex-1">Fee Setup</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-300 group-open:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </summary>
                <div class="mt-1 space-y-1 ml-4 pl-4 border-l-2 border-gray-100">
                    @can('Structure.view')
                    <a href="{{ route('finance.fee-structures.index') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.fee-structures.*') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Fee Structures
                    </a>
                    @endcan
                    <a href="{{ route('finance.fee-categories.index') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.fee-categories.*') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Fee Categories
                    </a>
                    <a href="{{ route('finance.scholarships.index') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.scholarships.*') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Scholarships
                    </a>
                </div>
            </details>

            <div class="mt-8 mb-2 px-4">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Reports</p>
            </div>

            <details class="group {{ request()->routeIs('finance.reports.*') ? 'open' : '' }} mb-1">
                <summary class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl cursor-pointer transition-colors duration-200 hover:bg-gray-50 hover:text-gray-900
                    {{ request()->routeIs('finance.reports.*') ? 'bg-gray-100 text-gray-900' : '' }} list-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m-6 0h6"/></svg>
                    <span class="mx-3 flex-1">All Reports</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-300 group-open:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </summary>
                <div class="mt-1 space-y-1 ml-4 pl-4 border-l-2 border-gray-100">
                    @can('report.financial')
                    <a href="{{ route('finance.reports.outstanding') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.reports.outstanding') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Outstanding Fees
                    </a>
                    <a href="{{ route('finance.reports.salary_sheet') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.reports.salary_sheet') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Salary Sheet
                    </a>
                    @endcan

                    @can('report.attendance')
                    <a href="{{ route('finance.reports.attendance.daily') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.reports.attendance.daily') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Daily Attendance
                    </a>
                    <a href="{{ route('finance.reports.attendance.class') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.reports.attendance.class') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Class Register
                    </a>
                    <a href="{{ route('finance.reports.attendance.student') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('finance.reports.attendance.student') ? 'text-blue-700 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                        Student Attendance
                    </a>
                    @endcan
                </div>
            </details>

            <div class="mt-8 mb-2 px-4">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Settings</p>
            </div>

            <a href="{{ route('finance.roles.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out mb-1
               {{ request()->routeIs('finance.roles.*') ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('finance.roles.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span class="mx-3">User Roles</span>
            </a>

        @endif


        @if(Auth::user()->role === 'admin')

            <a href="{{ route('admin.permissions.matrix') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out mb-1
               {{ request()->routeIs('admin.permissions.matrix') ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.permissions.matrix') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="mx-3">Role Permissions</span>
            </a>

            <a href="{{ route('admin.settings.edit') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out mb-1
               {{ request()->routeIs('admin.settings.*') ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.settings.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.82 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.82 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.82-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.82-3.31 2.37-2.37.526.315 1.144.37 1.748.163zM12 12a3 3 0 100-6 3 3 0 000 6z"/>
                </svg>
                <span class="mx-3">System Settings</span>
            </a>
        @endif

        <div class="h-20"></div> </div>
</div>
