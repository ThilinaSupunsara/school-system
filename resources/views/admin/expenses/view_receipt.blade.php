<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt: {{ $expense->description }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col overflow-hidden">

    <div class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center shadow-lg z-20">
        <div class="flex items-center gap-4">
            <div class="bg-blue-600 p-2 rounded-lg shadow-lg shadow-blue-900/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg leading-tight tracking-wide">{{ $expense->description }}</h1>
                <div class="flex items-center text-sm mt-0.5 space-x-3">
                    <span class="text-gray-400">{{ $expense->created_at->format('d M Y, h:i A') }}</span>
                    <span class="text-gray-600">&bull;</span>
                    <span class="text-green-400 font-mono font-bold bg-green-900/30 px-2 py-0.5 rounded text-xs">LKR {{ number_format($expense->amount_spent, 2) }}</span>
                </div>
            </div>
        </div>

        <button onclick="window.close()" class="text-gray-400 hover:text-white hover:bg-gray-700 p-2 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div class="flex-1 flex items-center justify-center p-4 sm:p-8 bg-gray-900 relative">
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#4b5563 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="relative z-10 w-full h-full flex items-center justify-center">
            <img src="{{ asset('storage/' . $expense->receipt_path) }}"
                 alt="Receipt for {{ $expense->description }}"
                 class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl ring-1 ring-gray-700 bg-white">
        </div>
    </div>

    <div class="sm:hidden bg-gray-800 border-t border-gray-700 p-4 text-center">
        <button onclick="window.close()" class="w-full bg-gray-700 text-white py-3 rounded-xl font-bold hover:bg-gray-600 active:bg-gray-500 transition shadow-lg">
            Close Viewer
        </button>
    </div>

</body>
</html>
