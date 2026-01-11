<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Account Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1">
                    <div class="sticky top-6 space-y-6">

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                            <div class="w-20 h-20 bg-gray-900 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ __('Active Member') }}
                                </span>
                            </div>
                        </div>

                        <nav class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <a href="#profile-info" class="flex items-center px-6 py-4 text-sm font-medium text-gray-700 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profile Information
                            </a>
                            <a href="#update-password" class="flex items-center px-6 py-4 text-sm font-medium text-gray-700 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 transition-colors border-t border-gray-100">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Security
                            </a>
                            <a href="#delete-account" class="flex items-center px-6 py-4 text-sm font-medium text-red-600 border-l-4 border-transparent hover:bg-red-50 hover:border-red-300 transition-colors border-t border-gray-100">
                                <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Danger Zone
                            </a>
                        </nav>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">

                    <div id="profile-info" class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-gray-100 scroll-mt-20">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div id="update-password" class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-gray-100 scroll-mt-20">
                        @include('profile.partials.update-password-form')
                    </div>

                    <div id="delete-account" class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-gray-100 scroll-mt-20">
                        @include('profile.partials.delete-user-form')
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
