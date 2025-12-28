<div class="flex flex-col h-screen w-64 bg-white border-r hidden md:flex">

    <div class="flex-shrink-0 flex flex-col items-center justify-center py-6 border-b border-gray-200 shadow-sm z-10">
        @if(isset($schoolSettings) && $schoolSettings->logo_path)
            <div class="h-16 w-16 rounded-full bg-white shadow-sm border border-gray-200 flex items-center justify-center overflow-hidden p-1">
                <img src="{{ asset('storage/' . $schoolSettings->logo_path) }}"
                     alt="Logo"
                     class="h-full w-full object-contain rounded-full">
            </div>
            <div class="mt-2 text-center px-2">
                <h1 class="text-xs font-bold text-gray-800 uppercase tracking-wide">
                    {{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}
                </h1>
            </div>
        @else
            <span class="text-lg font-bold text-gray-800">
                {{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}
            </span>
        @endif
    </div>

    <div class="flex-1 overflow-y-auto py-6">
        <nav class="space-y-2 px-4">

            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-200
               {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-bold text-blue-700' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="mx-4 font-medium">Dashboard</span>
            </a>

            @if(Auth::user()->role === 'teacher')
                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">My Tools</p>

                <a href="{{ route('teacher.attendance.class_list') }}"
                   class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200
                   {{ request()->routeIs('teacher.attendance.*') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="mx-4">Mark Attendance</span>
                </a>

                <a href="{{ route('teacher.payroll.index') }}"
                   class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200
                   {{ request()->routeIs('teacher.payroll.*') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m4 2h.01M17 16l4-4M10 20h4a2 2 0 002-2v-3a2 2 0 00-2-2h-4a2 2 0 00-2 2v3a2 2 0 002 2z"/></svg>
                    <span class="mx-4">My Payslips</span>
                </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'accountant']))

                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">Academic & Staff</p>

                @can('student.view')
                <a href="{{ route('finance.students.index') }}"
                   class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200
                   {{ request()->routeIs('finance.students.*') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="mx-4">Students</span>
                </a>
                @endcan
                @can('staff.view')
                <a href="{{ route('finance.staff.index') }}"
                   class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200
                   {{ request()->routeIs('finance.staff.*') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="mx-4">Staff Directory</span>
                </a>
                 @endcan

                <details class="group {{ request()->routeIs(['finance.grades.*', 'finance.sections.*']) ? 'open' : '' }}">
                    <summary class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 cursor-pointer
                        {{ request()->routeIs(['finance.grades.*', 'finance.sections.*']) ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span class="mx-4">Classes & Grades</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200 group-open:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </summary>
                    <div class="mt-2 space-y-1 ml-4 border-l border-gray-200 pl-4">
                        <a href="{{ route('finance.grades.index') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.grades.*') ? 'font-semibold text-blue-700' : '' }}">
                            • Grades Management
                        </a>
                        <a href="{{ route('finance.sections.index') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.sections.*') ? 'font-semibold text-blue-700' : '' }}">
                            • Sections (Classes)
                        </a>
                    </div>
                </details>

                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">Finance</p>

                @can('invoice.view')
                <a href="{{ route('finance.invoices.index') }}"
                   class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200
                   {{ request()->routeIs('finance.invoices.*') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M16 16v-3l-2.5-2.5L11 13V7m7 4a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="mx-4">Invoices & Fees</span>
                </a>
                @endcan

                @can('payroll.view')
                <a href="{{ route('finance.payroll.index') }}"
                   class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200
                   {{ request()->routeIs('finance.payroll.*') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8a4 4 0 100-8 4 4 0 000 8zm-8 4h16a2 2 0 002-2v-6a2 2 0 00-2-2H4a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    <span class="mx-4">Payroll</span>
                </a>
                @endcan

                <details class="group {{ request()->routeIs(['finance.expenses.*', 'finance.expense-categories.*']) ? 'open' : '' }}">
                    <summary class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 cursor-pointer
                        {{ request()->routeIs(['finance.expenses.*', 'finance.expense-categories.*']) ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m4 2h.01M17 16l4-4M10 20h4a2 2 0 002-2v-3a2 2 0 00-2-2h-4a2 2 0 00-2 2v3a2 2 0 002 2z"/></svg>
                        <span class="mx-4">Expenses</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200 group-open:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </summary>
                    <div class="mt-2 space-y-1 ml-4 border-l border-gray-200 pl-4">

                        @can('expense.view')
                        <a href="{{ route('finance.expenses.index') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.expenses.*') ? 'font-semibold text-blue-700' : '' }}">
                            • Other Expenses
                        </a>
                         @endcan

                        <a href="{{ route('finance.expense-categories.index') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.expense-categories.*') ? 'font-semibold text-blue-700' : '' }}">
                            • Expense Categories
                        </a>
                    </div>
                </details>

                <details class="group {{ request()->routeIs(['finance.fee-structures.*', 'finance.fee-categories.*', 'finance.scholarships.*']) ? 'open' : '' }}">
                    <summary class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 cursor-pointer
                        {{ request()->routeIs(['finance.fee-structures.*', 'finance.fee-categories.*', 'finance.scholarships.*']) ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span class="mx-4">Fee Setup</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200 group-open:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </summary>
                    <div class="mt-2 space-y-1 ml-4 border-l border-gray-200 pl-4">
                        @can('Structure.view')
                        <a href="{{ route('finance.fee-structures.index') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.fee-structures.*') ? 'font-semibold text-blue-700' : '' }}">
                            • Fee Structures
                        </a>
                        @endcan
                        <a href="{{ route('finance.fee-categories.index') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.fee-categories.*') ? 'font-semibold text-blue-700' : '' }}">
                            • Fee Categories
                        </a>
                        <a href="{{ route('finance.scholarships.index') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.scholarships.*') ? 'font-semibold text-blue-700' : '' }}">
                            • Scholarships
                        </a>
                    </div>
                </details>

                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">Reports</p>

                <details class="group {{ request()->routeIs('finance.reports.*') ? 'open' : '' }}">
                    <summary class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 cursor-pointer
                        {{ request()->routeIs('finance.reports.*') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m-6 0h6"/></svg>
                        <span class="mx-4">All Reports</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200 group-open:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </summary>
                    <div class="mt-2 space-y-1 ml-4 border-l border-gray-200 pl-4">
                        @can('report.financial')
                        <a href="{{ route('finance.reports.outstanding') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.reports.outstanding') ? 'font-semibold text-blue-700' : '' }}">
                            • Outstanding Fees
                        </a>

                        <a href="{{ route('finance.reports.salary_sheet') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.reports.salary_sheet') ? 'font-semibold text-blue-700' : '' }}">
                            • Salary Sheet
                        </a>
                        @endcan
                        @can('report.attendance')
                        <a href="{{ route('finance.reports.attendance.daily') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.reports.attendance.daily') ? 'font-semibold text-blue-700' : '' }}">
                            • Daily Attendance
                        </a>
                        <a href="{{ route('finance.reports.attendance.class') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.reports.attendance.class') ? 'font-semibold text-blue-700' : '' }}">
                            • Class Register
                        </a>
                        <a href="{{ route('finance.reports.attendance.student') }}" class="flex items-center py-1 text-sm text-gray-600 hover:text-blue-700 {{ request()->routeIs('finance.reports.attendance.student') ? 'font-semibold text-blue-700' : '' }}">
                            • Student Attendance
                        </a>
                        @endcan
                    </div>
                </details>
                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">User setting</p>

                <a href="{{ route('finance.roles.index') }}"
                   class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200
                   {{ request()->routeIs('finance.roles.*') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <span class="mx-4">User Roles</span>
                </a>

            @endif

            @if(Auth::user()->role === 'admin')
                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">Administration</p>

                <a href="{{ route('admin.permissions.matrix') }}"
                   class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200
                   {{ request()->routeIs('admin.permissions.matrix') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span class="mx-4">Role Permissions</span>
                </a>



                <a href="{{ route('admin.settings.edit') }}"
                   class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200
                   {{ request()->routeIs('admin.settings.*') ? 'bg-gray-200 font-medium text-blue-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.82 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.82 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.82-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.82-3.31 2.37-2.37.526.315 1.144.37 1.748.163zM12 12a3 3 0 100-6 3 3 0 000 6z"/></svg>
                    <span class="mx-4">System Settings</span>
                </a>
            @endif

            <div class="h-10"></div>

        </nav>
    </div>
</div>
