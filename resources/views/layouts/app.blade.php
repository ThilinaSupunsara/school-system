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

            /* 1. Scrollbar එකේ පළල (Width) */
        ::-webkit-scrollbar {
            width: 6px;  /* සිරස් (Vertical) Scrollbar පළල */
            height: 6px; /* තිරස් (Horizontal) Scrollbar උස */
        }

        /* 2. Scrollbar Track (පසුබිම) */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* 3. Scrollbar Thumb (අපිට අල්ලන්න පුළුවන් කොටස) */
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1; /* අළු පාට */
            border-radius: 10px; /* රවුම් හැඩය */
        }

        /* 4. Mouse එක ළඟට ගෙනාවම පාට වෙනස් වීම */
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Firefox සඳහා (Optional) */
        * {
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 #f1f1f1;
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
