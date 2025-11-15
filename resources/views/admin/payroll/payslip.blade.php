<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->staff->user->name }} - {{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} {{ $payroll->year }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg my-8 p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">Your School Name</h1>
            <p class="text-gray-600">Address of your school, City</p>
            <h2 class="text-2xl font-semibold mt-4">Payslip for {{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} {{ $payroll->year }}</h2>
        </div>

        <div class="mb-6 p-4 border rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Employee Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p><strong>Name:</strong> {{ $payroll->staff->user->name }}</p>
                    <p><strong>Designation:</strong> {{ $payroll->staff->designation }}</p>
                </div>
                <div>
                    <p><strong>Employee ID:</strong> STAFF-{{ $payroll->staff->id }}</p>
                    <p><strong>Join Date:</strong> {{ $payroll->staff->join_date }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-green-700 mb-2">Earnings</h3>
                <table class="min-w-full">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-2">Basic Salary</td>
                            <td class="py-2 text-right">LKR {{ number_format($payroll->basic_salary, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">Total Allowances</td>
                            <td class="py-2 text-right">LKR {{ number_format($payroll->total_allowances, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="border-t-2 border-gray-300">
                        <tr class="font-bold">
                            <td class="py-2">Gross Salary</td>
                            <td class="py-2 text-right">LKR {{ number_format($payroll->basic_salary + $payroll->total_allowances, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-red-700 mb-2">Deductions</h3>
                <table class="min-w-full">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-2">Total Deductions</td>
                            <td class="py-2 text-right">LKR {{ number_format($payroll->total_deductions, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="border-t-2 border-gray-300">
                        <tr class="font-bold">
                            <td class="py-2">Total Deductions</td>
                            <td class="py-2 text-right">LKR {{ number_format($payroll->total_deductions, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <h3 class="text-xl font-bold uppercase text-gray-800">Net Salary</h3>
            <p class="text-3xl font-extrabold text-blue-600">LKR {{ number_format($payroll->net_salary, 2) }}</p>
            </div>

        <div class="text-center mt-8 no-print">
            <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Print Payslip
            </button>
        </div>

    </div>
</body>
</html>
