<div class="flex flex-col h-screen w-64 bg-white border-r hidden md:flex">

    <div class="flex-shrink-0 flex flex-col items-center justify-center py-6  border-b border-gray-200 shadow-sm z-10">

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

            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-bold' : '' }}">
                <span class="mx-4 font-medium">Dashboard</span>
            </a>

            @if(Auth::user()->role === 'admin')
                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">Academic</p>

                <a href="{{ route('admin.grades.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('admin.grades.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Grades</span>
                </a>
                <a href="{{ route('admin.sections.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('admin.sections.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Classes</span>
                </a>
                <a href="{{ route('admin.students.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('admin.students.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Students</span>
                </a>

                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">HR & Settings</p>
                <a href="{{ route('admin.roles.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">User Roles</span>
                </a>
                <a href="{{ route('admin.staff.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('admin.staff.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Staff</span>
                </a>
                <a href="{{ route('admin.settings.edit') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Settings</span>
                </a>
                <a href="{{ route('admin.scholarships.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200">
                    <span class="mx-4">Scholarships</span>
                </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'accountant']))
                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">Finance</p>

                <a href="{{ route('finance.invoices.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('finance.invoices.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Invoices & Fees</span>
                </a>
                <a href="{{ route('finance.payroll.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('finance.payroll.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Payroll</span>
                </a>
                
                <a href="{{ route('finance.expense-categories.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 ...">
                    <span>Expense Categories</span>
                </a>
                <a href="{{ route('finance.expenses.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 ...">
                    <span>Other Expenses</span>
                </a>

                <a href="{{ route('finance.fee-structures.index') }}" class="flex items-center px-4 py-1 text-sm text-gray-500 hover:text-gray-900 ml-4">
                    • Fee Structure
                </a>
                <a href="{{ route('finance.fee-categories.index') }}" class="flex items-center px-4 py-1 text-sm text-gray-500 hover:text-gray-900 ml-4">
                    • Fee Categories
                </a>

                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">Reports</p>
                <a href="{{ route('attendance.reports.attendance.daily') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('admin.reports.attendance.daily') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Daily Attendance</span>
                </a>
                <a href="{{ route('attendance.reports.attendance.class') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('admin.reports.attendance.class') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Class Register</span>
                </a>
                <a href="{{ route('attendance.reports.attendance.student') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('admin.reports.attendance.class') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">student Attendance</span>
                </a>
                <a href="{{ route('finance.reports.outstanding') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('finance.reports.outstanding') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Outstanding Fees</span>
                </a>
                 <a href="{{ route('finance.reports.salary_sheet') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('finance.reports.salary_sheet') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Salary Sheet</span>
                </a>
            @endif

            @if(Auth::user()->role === 'teacher')
                <p class="px-4 pt-4 text-xs font-bold text-gray-400 uppercase">My Class</p>

                <a href="{{ route('teacher.attendance.class_list') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('teacher.attendance.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">Mark Attendance</span>
                </a>
                <a href="{{ route('teacher.payroll.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 {{ request()->routeIs('teacher.payroll.*') ? 'bg-gray-200' : '' }}">
                    <span class="mx-4">My Payslips</span>
                </a>
            @endif

            <div class="h-10"></div>

        </nav>
    </div>
</div>
