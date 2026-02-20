<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Conflict Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">
                            {{ __('Found') }} {{ count($conflicts) }} {{ __('conflicts') }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __('The import process detected conflicts between imported data and existing records. Please review below:') }}
                        </p>
                    </div>

                    <div class="space-y-6">
                        @foreach($conflicts as $index => $conflict)
                            <div class="border rounded-lg p-4 bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-semibold text-red-800 dark:text-red-200">
                                            {{ __('Conflict') }} {{ $index + 1 }} of {{ count($conflicts) }}
                                        </h4>
                                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                                            {{ $conflict['message'] }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 bg-red-200 dark:bg-red-700 text-red-800 dark:text-red-200 rounded text-sm font-semibold">
                                        {{ ucfirst($conflict['type']) }} Conflict
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <h5 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Current Data') }}
                                        </h5>
                                        <div class="bg-white dark:bg-gray-700 p-3 rounded text-sm">
                                            @if($conflict['type'] == 'layer')
                                                <p><strong>Order:</strong> {{ $conflict['existing']['layer_order'] }}</p>
                                                <p><strong>Thickness:</strong> {{ $conflict['existing']['thickness'] }}</p>
                                                <p><strong>Width:</strong> {{ $conflict['existing']['width'] }}</p>
                                                <p><strong>Angle:</strong> {{ $conflict['existing']['angle'] }}°</p>
                                            @else
                                                <p><strong>Name:</strong> {{ $conflict['existing']->name ?? $conflict['existing']['name'] }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Incoming Data') }}
                                        </h5>
                                        <div class="bg-white dark:bg-gray-700 p-3 rounded text-sm">
                                            @if($conflict['type'] == 'layer')
                                                <p><strong>Order:</strong> {{ $conflict['incoming']['layer_order'] }}</p>
                                                <p><strong>Thickness:</strong> {{ $conflict['incoming']['thickness'] }}</p>
                                                <p><strong>Width:</strong> {{ $conflict['incoming']['width'] }}</p>
                                                <p><strong>Angle:</strong> {{ $conflict['incoming']['angle'] }}°</p>
                                            @else
                                                <p><strong>Name:</strong> {{ $conflict['incoming']['name'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($conflict['type'] == 'layer')
                                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded text-sm">
                                        <p class="text-blue-800 dark:text-blue-200">
                                            <strong>Layup:</strong> {{ $conflict['layup_name'] }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded">
                        <p class="text-yellow-800 dark:text-yellow-200">
                            {{ __('The import was aborted due to conflicts. You can:') }}
                        </p>
                        <ul class="list-disc list-inside text-sm text-yellow-700 dark:text-yellow-300 mt-2">
                            <li>{{ __('Review the conflicts above and retry with a different strategy') }}</li>
                            <li>{{ __('Modify your import file to resolve the conflicts') }}</li>
                        </ul>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('import-export.import') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Try Again') }}
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Back to Suppliers') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
