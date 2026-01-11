<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <style>
            /* Select2 Customization to match Tailwind Inputs */
            .select2-container .select2-selection--single {
                height: 42px !important;
                border-color: #e5e7eb !important; /* gray-200 */
                border-radius: 0.5rem !important; /* rounded-lg */
                display: flex;
                align-items: center;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px !important;
            }

            /* --- Modern Scrollbar Design --- */

            /* Global Scrollbar (Main Body) */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            ::-webkit-scrollbar-track {
                background: transparent;
            }
            ::-webkit-scrollbar-thumb {
                background: #cbd5e1; /* slate-300 */
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #94a3b8; /* slate-400 */
            }

            /* Specific Sidebar Scrollbar (Thinner & cleaner) */
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #e2e8f0; /* slate-200 */
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #cbd5e1;
            }

            /* Firefox Support */
            * {
                scrollbar-width: thin;
                scrollbar-color: #cbd5e1 transparent;
            }
            .custom-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: #e2e8f0 transparent;
            }
        </style>
    </head>

    <body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: window.innerWidth >= 768 }">

        <div class="h-screen flex overflow-hidden bg-gray-50">

            <div :class="sidebarOpen ? 'w-72' : 'w-0'"
                 class="transition-all duration-300 ease-in-out flex-shrink-0 overflow-hidden shadow-xl z-20 bg-white">
                @include('layouts.sidebar')
            </div>

            <div class="flex-1 flex flex-col h-screen w-full relative overflow-hidden">

                @include('layouts.navigation')

                <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-50/50 p-4 sm:p-6 lg:p-8 custom-scrollbar">

                    @if (isset($header))
                        <header class="mb-6">
                            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                                {{ $header }}
                            </h1>
                        </header>
                    @endif

                    <div class="fade-in">
                        {{ $slot }}
                    </div>

                    <div class="h-10"></div>
                </main>

            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            // Optional: Close sidebar when clicking main content on mobile if you want
            // Not strictly necessary but good for UX
        </script>
    </body>
</html>
