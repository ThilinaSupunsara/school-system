<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('School Settings') }}
                </h2>
                <p class="text-sm text-gray-500">Manage basic school information and branding.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">General Information</h3>
                    <p class="text-sm text-gray-500">These details will appear on invoices and reports.</p>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                            <div class="md:col-span-2 space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">School Name</label>
                                    <input type="text" name="school_name" value="{{ old('school_name', $settings->school_name) }}" required
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                                    <textarea name="school_address" rows="3"
                                              class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors">{{ old('school_address', $settings->school_address) }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                                        <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}"
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', $settings->email) }}"
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-bold text-gray-700 mb-2">School Logo</label>

                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-gray-50 transition-colors cursor-pointer relative group">

                                    <div class="mb-4 h-32 w-full flex items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                                        @if($settings->logo_path)
                                            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="h-full w-full object-contain">
                                        @else
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>

                                    <div class="text-sm text-gray-600">
                                        <span class="text-blue-600 font-semibold group-hover:underline">Upload a file</span> or drag and drop
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>

                                    <input type="file" name="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                            </div>

                        </div>

                        <div class="flex justify-end pt-6 mt-6 border-t border-gray-100">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
