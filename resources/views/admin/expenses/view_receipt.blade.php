<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt: {{ $expense->description }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="fixed top-4 right-4">
        <button onclick="window.close()" class="bg-red-600 text-white px-4 py-2 rounded-full font-bold hover:bg-red-700 shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Close
        </button>
    </div>

    <div class="bg-white p-2 rounded-lg shadow-2xl max-w-4xl w-full">
        <div class="p-4 border-b mb-2 flex justify-between items-center bg-gray-50 rounded-t">
            <div>
                <h2 class="font-bold text-lg text-gray-800">{{ $expense->description }}</h2>
                <p class="text-sm text-gray-500">{{ $expense->created_at->format('Y-m-d h:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-bold">
                    LKR {{ number_format($expense->amount_spent, 2) }}
                </span>
            </div>
        </div>

        <div class="overflow-auto max-h-[80vh] flex justify-center bg-gray-200 rounded">
            <img src="{{ asset('storage/' . $expense->receipt_path) }}"
                 alt="Receipt"
                 class="max-w-full object-contain">
        </div>

        <div class="mt-4 text-center p-2">
            <button onclick="window.close()" class="bg-gray-700 text-white px-8 py-2 rounded hover:bg-gray-600 font-bold">
                Close Window
            </button>
        </div>
    </div>

</body>
</html>
