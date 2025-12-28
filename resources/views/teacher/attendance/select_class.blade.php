<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Class for Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold">Your Assigned Classes</h3>
                        <span class="text-sm text-gray-500">Date: {{ date('Y-m-d') }}</span>
                    </div>

                    @if ($assignedSections->isEmpty())
                        <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="mt-2 text-gray-500 font-medium">No classes assigned as Class Teacher.</p>
                            <p class="text-sm text-gray-400">Please contact administration.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach ($assignedSections as $section)
                                
                                <a href="{{ route('teacher.attendance.mark.form', $section->id) }}" 
                                   class="block p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 relative overflow-hidden group
                                   {{ $section->is_marked ? 'bg-green-600 text-white' : 'bg-blue-600 text-white' }}">
                                    
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm opacity-90 uppercase tracking-wider font-semibold">Grade & Section</p>
                                            <p class="font-bold text-3xl mt-1">{{ $section->grade->name }} - {{ $section->name }}</p>
                                        </div>
                                        
                                        @if($section->is_marked)
                                            <div class="bg-green-500 p-2 rounded-full bg-opacity-50">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @else
                                            <div class="bg-blue-500 p-2 rounded-full bg-opacity-50">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <p class="mt-2 opacity-90 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        {{ $section->students->count() }} Students
                                    </p>

                                    <div class="mt-6">
                                        @if($section->is_marked)
                                            <div class="flex justify-between items-center bg-green-700 bg-opacity-40 rounded-lg p-2 px-3">
                                                <span class="flex items-center text-sm font-bold">
                                                    <span class="w-2 h-2 bg-green-300 rounded-full mr-2 animate-pulse"></span>
                                                    Attendance Marked
                                                </span>
                                                <span class="text-xs border border-white border-opacity-30 px-2 py-1 rounded hover:bg-white hover:text-green-700 transition">Edit</span>
                                            </div>
                                        @else
                                            <div class="flex justify-between items-center bg-blue-700 bg-opacity-40 rounded-lg p-2 px-3">
                                                <span class="flex items-center text-sm font-bold">
                                                    <span class="w-2 h-2 bg-yellow-300 rounded-full mr-2 animate-pulse"></span>
                                                    Pending
                                                </span>
                                                <span class="text-xs bg-white text-blue-600 px-3 py-1 rounded-full font-bold shadow hover:bg-blue-50 transition">Mark Now &rarr;</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>