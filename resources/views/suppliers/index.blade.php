<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Suppliers') }}
            </h2>
            <a href="{{ route('suppliers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Add Supplier') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($message = Session::get('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ $message }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(count($suppliers) > 0)
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 px-4">ID</th>
                                    <th class="text-left py-3 px-4">Name</th>
                                    <th class="text-left py-3 px-4">Layups</th>
                                    <th class="text-left py-3 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-3 px-4">{{ $supplier->id }}</td>
                                        <td class="py-3 px-4">{{ $supplier->name }}</td>
                                        <td class="py-3 px-4">{{ count($supplier->layups) }}</td>
                                        <td class="py-3 px-4 space-x-2">
                                            <a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-500 hover:text-blue-700">View</a>
                                            <a href="{{ route('suppliers.edit', $supplier) }}" class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                            <a href="{{ route('import-export.export-form', $supplier) }}" class="text-green-500 hover:text-green-700">Export</a>
                                            <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">No suppliers found.</p>
                    @endif

                    <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('import-export.import') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Import Supplier') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
