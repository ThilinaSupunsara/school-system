<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="p-2 rounded-full bg-white text-gray-600 shadow-sm border border-gray-200 hover:bg-gray-50 hover:text-blue-600 transition-all duration-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Class Teacher Assignment') }}
                </h2>
                <p class="text-sm text-gray-500">
                    Target Class: <span class="font-bold text-gray-800">{{ $section->grade->name }} - {{ $section->name }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="flex items-center p-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Current Status</h3>
                </div>

                <div class="p-6">
                    @if ($section->classTeacher)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <img class="h-16 w-16 rounded-full border-4 border-green-50"
                                         src="https://ui-avatars.com/api/?name={{ urlencode($section->classTeacher->user->name) }}&color=059669&background=d1fae5&size=128"
                                         alt="">
                                    <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-full ring-2 ring-white bg-green-400"></span>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $section->classTeacher->user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $section->classTeacher->designation ?? 'Teacher' }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                        Active Class Teacher
                                    </span>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('finance.sections.assign_teacher.remove', $section->id) }}">

                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="confirmDelete(event)" class="flex items-center text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Remove
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <div class="bg-yellow-50 p-3 rounded-full mb-3">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-800">No Teacher Assigned</h4>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto mt-1">This section currently does not have a class teacher. Use the form below to assign one.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100">
                <div class="px-8 py-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $section->classTeacher ? 'Change Teacher' : 'Assign Teacher' }}
                    </h3>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('finance.sections.assign_teacher.store', $section->id) }}">
                        @csrf

                        <div class="mb-6">
                            <label for="class_teacher_id" class="block text-sm font-bold text-gray-700 mb-2">Select Staff Member</label>
                            <div class="relative">
                                <select name="class_teacher_id" id="class_teacher_id" required
                                        class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3 pl-4 pr-10 appearance-none">
                                    <option value="">-- Choose a Teacher --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ $section->class_teacher_id == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->user->name }} ({{ $teacher->designation }})
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('finance.sections.index') }}" class="mr-4 text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>

                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-900 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider hover:bg-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $section->classTeacher ? 'Update Assignment' : 'Assign Teacher' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
    function confirmDelete(event) {
     
        event.preventDefault();


        const form = event.target.closest('form');


        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
</x-app-layout>
