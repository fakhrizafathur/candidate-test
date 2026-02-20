<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Layer') }} #{{ $layer->layer_order }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('suppliers.layups.layers.edit', [$supplier, $layup, $layer]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('suppliers.layups.layers.index', [$supplier, $layup]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Layer Information</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ID') }}</p>
                                <p class="font-semibold">{{ $layer->id }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Supplier') }}</p>
                                <p class="font-semibold">
                                    <a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-500">{{ $supplier->name }}</a>
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Layup') }}</p>
                                <p class="font-semibold">
                                    <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}" class="text-blue-500">{{ $layup->name }}</a>
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Layer Order') }}</p>
                                <p class="font-semibold">{{ $layer->layer_order }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Thickness') }}</p>
                                <p class="font-semibold">{{ $layer->thickness }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Width') }}</p>
                                <p class="font-semibold">{{ $layer->width }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Angle') }}</p>
                                <p class="font-semibold">{{ $layer->angle }}°</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Created') }}</p>
                                <p class="font-semibold">{{ $layer->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t">
                            <form method="POST" action="{{ route('suppliers.layups.layers.destroy', [$supplier, $layup, $layer]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">
                                    {{ __('Delete Layer') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
