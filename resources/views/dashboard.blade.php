<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Suppliers Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Suppliers') }}</p>
                                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $statistics['total_suppliers'] }}</p>
                            </div>
                            <div class="text-blue-500 text-4xl">
                                📦
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Layups Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Layups') }}</p>
                                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $statistics['total_layups'] }}</p>
                            </div>
                            <div class="text-green-500 text-4xl">
                                📋
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Layers Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Layers') }}</p>
                                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $statistics['total_layers'] }}</p>
                            </div>
                            <div class="text-purple-500 text-4xl">
                                📐
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suppliers with Data Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Active Suppliers') }}</p>
                                <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $statistics['suppliers_with_data'] }}</p>
                            </div>
                            <div class="text-orange-500 text-4xl">
                                ✅
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Quick Actions') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('suppliers.create') }}" class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition">
                            <div>
                                <p class="font-semibold text-blue-900 dark:text-blue-100">{{ __('Create Supplier') }}</p>
                                <p class="text-sm text-blue-700 dark:text-blue-300">{{ __('Add a new supplier') }}</p>
                            </div>
                            <span class="text-2xl">➕</span>
                        </a>

                        <a href="{{ route('suppliers.index') }}" class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition">
                            <div>
                                <p class="font-semibold text-green-900 dark:text-green-100">{{ __('View Suppliers') }}</p>
                                <p class="text-sm text-green-700 dark:text-green-300">{{ __('Manage all suppliers') }}</p>
                            </div>
                            <span class="text-2xl">👁️</span>
                        </a>

                        <a href="{{ route('import-export.import') }}" class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition">
                            <div>
                                <p class="font-semibold text-purple-900 dark:text-purple-100">{{ __('Import Data') }}</p>
                                <p class="text-sm text-purple-700 dark:text-purple-300">{{ __('Import supplier data') }}</p>
                            </div>
                            <span class="text-2xl">📥</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Suppliers -->
            @if($recent_suppliers->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Recent Suppliers') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b dark:border-gray-700">
                                        <th class="text-left py-3 px-4">{{ __('Supplier') }}</th>
                                        <th class="text-left py-3 px-4">{{ __('Layups') }}</th>
                                        <th class="text-left py-3 px-4">{{ __('Layers') }}</th>
                                        <th class="text-left py-3 px-4">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_suppliers as $supplier)
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="py-3 px-4 font-semibold">{{ $supplier->name }}</td>
                                            <td class="py-3 px-4">
                                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-xs font-semibold">
                                                    {{ $supplier->layups_count }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs font-semibold">
                                                    {{ $supplier->layups_layers_count }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 space-x-2">
                                                <a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-500 hover:text-blue-700 text-xs font-semibold">{{ __('View') }}</a>
                                                <a href="{{ route('import-export.export-form', $supplier) }}" class="text-green-500 hover:text-green-700 text-xs font-semibold">{{ __('Export') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <p class="text-gray-500 dark:text-gray-400">{{ __('No suppliers yet. Create one to get started!') }}</p>
                        <a href="{{ route('suppliers.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Create First Supplier') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
