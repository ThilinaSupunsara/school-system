<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Payslips') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class_alias="px-6 py-3 text-left ...">Month/Year</th>
                                    <th class_alias="px-6 py-3 text-left ...">Net Salary</th>
                                    <th class_alias="px-6 py-3 text-left ...">Status</th>
                                    <th class_alias="relative px-6 py-3"><span class_alias="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($payrolls as $payroll)
                                    <tr>
                                        <td class="px-6 py-4 ...">
                                            {{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} / {{ $payroll->year }}
                                        </td>
                                        <td class="px-6 py-4 ...">LKR {{ number_format($payroll->net_salary, 2) }}</td>
                                        <td class="px-6 py-4 ...">
                                            @if($payroll->status == 'paid')
                                                <span class_alias="px-2 ... bg-green-100 text-green-800">Paid</span>
                                            @else
                                                <span class_alias="px-2 ... bg-blue-100 text-blue-800">Generated</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right ...">
                                            <a href="{{ route('teacher.payroll.show', $payroll->id) }}" class="text-indigo-600 hover:text-indigo-900" target="_blank">
                                                View Payslip
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No payslip records found.
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
