<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $layup->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('suppliers.layups.edit', [$supplier, $layup]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('suppliers.layups.index', $supplier) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back') }}
                </a>
            </div>
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
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Layup Information</h3>
                        <p><strong>ID:</strong> {{ $layup->id }}</p>
                        <p><strong>Name:</strong> {{ $layup->name }}</p>
                        <p><strong>Supplier:</strong> <a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-500">{{ $supplier->name }}</a></p>
                        <p><strong>Created:</strong> {{ $layup->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Layers</h3>
                            <a href="{{ route('suppliers.layups.layers.create', [$supplier, $layup]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                {{ __('Add Layer') }}
                            </a>
                        </div>

                        @if(count($layup->layers) > 0)
                            <table class="w-full border">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="text-left py-3 px-4">Order</th>
                                        <th class="text-left py-3 px-4">Thickness</th>
                                        <th class="text-left py-3 px-4">Width</th>
                                        <th class="text-left py-3 px-4">Angle</th>
                                        <th class="text-left py-3 px-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($layup->layers->sortBy('layer_order') as $layer)
                                        <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="py-3 px-4">{{ $layer->layer_order }}</td>
                                            <td class="py-3 px-4">{{ $layer->thickness }}</td>
                                            <td class="py-3 px-4">{{ $layer->width }}</td>
                                            <td class="py-3 px-4">{{ $layer->angle }}°</td>
                                            <td class="py-3 px-4 space-x-2">
                                                <a href="{{ route('suppliers.layups.layers.show', [$supplier, $layup, $layer]) }}" class="text-blue-500 hover:text-blue-700">View</a>
                                                <a href="{{ route('suppliers.layups.layers.edit', [$supplier, $layup, $layer]) }}" class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                                <form method="POST" action="{{ route('suppliers.layups.layers.destroy', [$supplier, $layup, $layer]) }}" style="display:inline;">
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
                            <p class="text-gray-500">No layers found. <a href="{{ route('suppliers.layups.layers.create', [$supplier, $layup]) }}" class="text-blue-500">Create one</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
