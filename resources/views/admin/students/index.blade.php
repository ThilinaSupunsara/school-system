<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Students Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('admin.students.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Register New Student') }}
                        </a>
                    </div>

                    <form method="GET" action="{{ route('admin.students.index') }}" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Search</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Adm No..." class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                            </div>

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
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                                data-grade-id="{{ $section->grade_id }}"
                                                {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->grade->name }} - {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end space-x-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 font-bold w-full">
                                    Filter
                                </button>
                                <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-400 font-bold w-full text-center">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>
                    <script>
                        function filterSections() {
                            // 1. තෝරාගත් Grade ID එක ගන්න
                            var selectedGradeId = document.getElementById('grade_filter').value;
                            var sectionSelect = document.getElementById('section_filter');
                            var options = sectionSelect.options;

                            // 2. මුලින්ම Section selection එක reset කරන්න (Grade එක මාරු කළාම පරණ section එක අයින් වෙන්න)
                            if (selectedGradeId !== "") {
                                sectionSelect.value = "";
                            }

                            // 3. හැම Section option එකක්ම check කරන්න
                            for (var i = 0; i < options.length; i++) {
                                var option = options[i];
                                var sectionGradeId = option.getAttribute('data-grade-id');

                                // "All Sections" option එක හැමවිටම පෙන්වන්න
                                if (option.value === "") {
                                    continue;
                                }

                                // Grade එකක් තෝරාගෙන නැත්නම් OR Grade ID එක මැච් වෙනවා නම් පෙන්වන්න
                                if (selectedGradeId === "" || sectionGradeId == selectedGradeId) {
                                    option.style.display = "block"; // පෙන්වන්න
                                } else {
                                    option.style.display = "none";  // හංගන්න
                                }
                            }
                        }

                        // Page එක Load වෙද්දී (Filter කරලා ආවාම) කලින් තෝරාගත් Grade එකට අනුව Sections හදන්න
                        document.addEventListener("DOMContentLoaded", function() {
                            filterSections();
                            // නමුත් selected value එක reset නොවී තියන්න (PHP වලින් එන value එක තියන්න)
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adm. No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent's Phone</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Edit</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->admission_no }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $student->section->grade->name }} - {{ $student->section->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->parent_phone }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.students.edit', $student->id) }}" class="text-indigo-600 hover:text-indigo-900">View/Edit</a>


                                            </td>
                                            
                                        <td>
                                            <form class="inline" method="POST" action="{{ route('admin.students.destroy', $student->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this student?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">
                                                Delete
                                            </button>
                                        </form>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $students->links() }}
                    </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
