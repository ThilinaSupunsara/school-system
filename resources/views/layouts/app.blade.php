<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <linkpreconnect href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container .select2-selection--single {
                height: 42px !important;
                border-color: #d1d5db !important;
                display: flex;
                align-items: center;
            }
        </style>
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: window.innerWidth >= 768 }">

    <div class="h-screen bg-gray-100 flex overflow-hidden">

        <div :class="sidebarOpen ? 'w-64' : 'w-0'" class="transition-all duration-300 ease-in-out flex-shrink-0 overflow-hidden">
            @include('layouts.sidebar')
        </div>

        <div class="flex-1 flex flex-col h-screen w-full">

            @include('layouts.navigation')

            <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-100">
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <div class="py-6">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>
