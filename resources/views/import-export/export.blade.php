<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Export Supplier') }}: {{ $supplier->name }}
            </h2>
            <a href="{{ route('suppliers.show', $supplier) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Export Information') }}</h3>
                        <ul class="list-disc list-inside space-y-2">
                            <li><strong>Supplier:</strong> {{ $supplier->name }}</li>
                            <li><strong>Total Layups:</strong> {{ count($supplier->layups) }}</li>
                            <li><strong>Total Layers:</strong> {{ $supplier->layups->sum(fn($l) => count($l->layers)) }}</li>
                        </ul>
                    </div>

                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Export Format') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            {{ __('Select the format in which you want to export the supplier data along with all related layups and layers.') }}
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- JSON Format -->
                            <a href="{{ route('import-export.export', $supplier) }}?format=json" class="border-2 border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:border-blue-500 dark:hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/10 cursor-pointer transition block no-underline">
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ __('JSON') }}</h4>
                                    <span class="text-2xl">📄</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    {{ __('Standard JSON format for data interchange and integration with other systems.') }}
                                </p>
                                <ul class="text-xs text-gray-500 dark:text-gray-500 space-y-1">
                                    <li>✓ {{ __('Machine readable') }}</li>
                                    <li>✓ {{ __('Easy re-import') }}</li>
                                    <li>✓ {{ __('System integration') }}</li>
                                </ul>
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-blue-600 dark:text-blue-400 font-semibold text-sm">{{ __('Download JSON') }} →</span>
                                </div>
                            </a>

                            <!-- CSV Format -->
                            <a href="{{ route('import-export.export', $supplier) }}?format=csv" class="border-2 border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:border-green-500 dark:hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/10 cursor-pointer transition block no-underline">
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ __('CSV') }}</h4>
                                    <span class="text-2xl">📊</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    {{ __('Comma-separated values format for use in spreadsheet applications.') }}
                                </p>
                                <ul class="text-xs text-gray-500 dark:text-gray-500 space-y-1">
                                    <li>✓ {{ __('Excel/Sheets compatible') }}</li>
                                    <li>✓ {{ __('Easy to edit') }}</li>
                                    <li>✓ {{ __('Wide support') }}</li>
                                </ul>
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-green-600 dark:text-green-400 font-semibold text-sm">{{ __('Download CSV') }} →</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
