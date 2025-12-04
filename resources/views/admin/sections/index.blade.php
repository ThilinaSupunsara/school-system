<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sections Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('admin.sections.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Add New Section') }}
                        </a>
                    </div>

                    <form method="GET" action="{{ route('admin.sections.index') }}" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grade</label>
                                <select name="grade_id" id="grade_filter" onchange="filterSections()" class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                                    <option value="">All Grades</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Section</label>
                                <select name="section_id" id="section_filter" class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                                    <option value="">All Sections</option>
                                    @foreach ($allSections as $sec)
                                        <option value="{{ $sec->id }}"
                                                data-grade-id="{{ $sec->grade_id }}"
                                                {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                            {{ $sec->name }} ({{ $sec->grade->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end space-x-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 font-bold w-1/2">
                                    Filter
                                </button>
                                <a href="{{ route('admin.sections.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-400 font-bold w-1/2 text-center">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>
                    <script>
                        function filterSections() {
                            var selectedGradeId = document.getElementById('grade_filter').value;
                            var sectionSelect = document.getElementById('section_filter');
                            var options = sectionSelect.options;

                            // Grade එක මාරු කළොත් Section එක Reset කරන්න (නැත්නම් පරණ ID එකම තියෙන්න පුළුවන්)
                            if (selectedGradeId !== "") {
                                // sectionSelect.value = ""; // අවශ්‍ය නම් මෙය uncomment කරන්න
                            }

                            for (var i = 0; i < options.length; i++) {
                                var option = options[i];
                                var sectionGradeId = option.getAttribute('data-grade-id');

                                // "All Sections" option එක හැමවිටම පෙන්වන්න
                                if (option.value === "") {
                                    continue;
                                }

                                // Grade එකක් තෝරාගෙන නැත්නම් OR Grade ID එක මැච් වෙනවා නම් පෙන්වන්න
                                if (selectedGradeId === "" || sectionGradeId == selectedGradeId) {
                                    option.style.display = "block";
                                } else {
                                    option.style.display = "none";
                                }
                            }

                            // Grade එකක් තෝරාගෙන ඇත්නම්, පෙන්වන පළමු valid option එක select කරවන්න (UX සඳහා)
                            if (selectedGradeId !== "") {
                                sectionSelect.value = "";
                            }
                        }

                        // Page Load එකේදී Filter එක run කරන්න
                        document.addEventListener("DOMContentLoaded", function() {
                            filterSections();
                            // පරණ අගය තියාගන්න
                            var oldSection = "{{ request('section_id') }}";
                            if(oldSection) {
                                document.getElementById('section_filter').value = oldSection;
                            }
                        });
                    </script>
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Grade
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Section Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Class Teacher
                                    </th>
                                    <th class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($sections as $section)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $section->grade->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold">
                                            {{ $section->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($section->classTeacher)
                                                <span class="text-blue-600 font-medium">{{ $section->classTeacher->user->name }}</span>
                                            @else
                                                <span class="text-gray-400 italic">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                            <a href="{{ route('admin.sections.edit', $section->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                                            <a href="{{ route('admin.sections.assign_teacher.form', $section->id) }}" class="text-green-600 hover:text-green-900 mr-3">
                                                Assign Teacher
                                            </a>

                                            <form class="inline" method="POST" action="{{ route('admin.sections.destroy', $section->id) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this section?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No sections found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $sections->links() }}
                    </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
