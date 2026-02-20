<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Layer for') }} {{ $layup->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('suppliers.layups.layers.store', [$supplier, $layup]) }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="layer_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Layer Order') }}
                            </label>
                            <input type="number" id="layer_order" name="layer_order" value="{{ old('layer_order') }}" min="1" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('layer_order')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="thickness" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Thickness') }}
                            </label>
                            <input type="number" id="thickness" name="thickness" value="{{ old('thickness') }}" step="0.01" min="0.01" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('thickness')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="width" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Width') }}
                            </label>
                            <input type="number" id="width" name="width" value="{{ old('width') }}" step="0.01" min="0.01" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('width')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="angle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Angle') }}
                            </label>
                            <select id="angle" name="angle" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">{{ __('Select angle') }}</option>
                                <option value="0" {{ old('angle') == '0' ? 'selected' : '' }}>0°</option>
                                <option value="45" {{ old('angle') == '45' ? 'selected' : '' }}>45°</option>
                                <option value="90" {{ old('angle') == '90' ? 'selected' : '' }}>90°</option>
                            </select>
                            @error('angle')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Create') }}
                            </button>
                            <a href="{{ route('suppliers.layups.layers.index', [$supplier, $layup]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
