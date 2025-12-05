<x-app-layout>
    <x-slot name="header">{{ __('Manage Categories') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-4">Existing Categories</h3>
                    <table class="w-full border">
                        @foreach($categories as $cat)
                        <tr class="border-b">
                            <td class="p-3">{{ $cat->name }}</td>
                            <td class="p-3 text-right">
                                <form action="{{ route('finance.expense-categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 font-bold text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="font-bold text-lg mb-4">Add New Category</h3>
                    <form action="{{ route('finance.expense-categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Category Name</label>
                            <input type="text" name="name" class="w-full rounded border-gray-300" placeholder="e.g. Transport" required>
                        </div>
                        <button class="w-full bg-blue-800 text-white py-2 rounded font-bold">Add Category</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
