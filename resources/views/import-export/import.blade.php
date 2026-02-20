<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Import Supplier') }}
            </h2>
            <a href="{{ route('suppliers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($message = Session::get('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ $message }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('import-export.import-action') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Target Supplier') }}
                            </label>
                            <select id="supplier_id" name="supplier_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">{{ __('Select supplier') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Import File') }}
                            </label>
                            <input type="file" id="file" name="file" accept=".json,.csv" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <p class="mt-2 text-sm text-gray-500">{{ __('Accepted formats: JSON, CSV') }}</p>
                            @error('file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="conflict_strategy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Conflict Resolution Strategy') }}
                            </label>
                            <select id="conflict_strategy" name="conflict_strategy" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">{{ __('Select strategy') }}</option>
                                <option value="skip">{{ __('Skip Conflict') }} - {{ __('Keep current data') }}</option>
                                <option value="overwrite">{{ __('Overwrite Existing') }} - {{ __('Replace with imported data') }}</option>
                                <option value="duplicate">{{ __('Duplicate Layup') }} - {{ __('Create new layup with suffix') }}</option>
                                <option value="reject">{{ __('Reject Entire Import') }} - {{ __('Abort on conflict') }}</option>
                            </select>
                            @error('conflict_strategy')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded">
                            <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">{{ __('Conflict Detection') }}</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-200">
                                {{ __('The system will detect conflicts when:') }}
                            </p>
                            <ul class="list-disc list-inside text-sm text-blue-700 dark:text-blue-200 mt-2">
                                <li>{{ __('A layup with the same name already exists under the supplier') }}</li>
                                <li>{{ __('A layer with the same order has different thickness, width, or angle') }}</li>
                            </ul>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Import') }}
                            </button>
                            <a href="{{ route('suppliers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg mb-6">{{ __('Import File Formats') }}</h3>
                    
                    <!-- JSON Format -->
                    <div class="mb-8 pb-8 border-b dark:border-gray-700">
                        <h4 class="font-semibold text-blue-600 dark:text-blue-400 mb-3">JSON Format</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            {{ __('Use this format for complete data structure with nested layers.') }}
                        </p>
                        <pre class="bg-gray-100 dark:bg-gray-700 p-4 rounded overflow-auto text-xs"><code>{
  "supplier": {
    "id": 1,
    "name": "Supplier Name"
  },
  "layups": [
    {
      "name": "Layup Name",
      "layers": [
        {
          "layer_order": 1,
          "thickness": 10.5,
          "width": 100.0,
          "angle": 0
        }
      ]
    }
  ]
}</code></pre>
                    </div>

                    <!-- CSV Format -->
                    <div class="mb-8 pb-8 border-b dark:border-gray-700">
                        <h4 class="font-semibold text-green-600 dark:text-green-400 mb-3">CSV Format</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            {{ __('Use comma-separated values. First row must be the header.') }}
                        </p>
                        <pre class="bg-gray-100 dark:bg-gray-700 p-4 rounded overflow-auto text-xs"><code>supplier_id,supplier_name,layup_id,layup_name,layer_id,layer_order,thickness,width,angle
1,Supplier A,1,Standard Layup,1,1,10.5,100,0
1,Supplier A,1,Standard Layup,2,2,12.0,100,45
1,Supplier A,2,Premium Layup,3,1,15.5,120,0</code></pre>
                    </div>

                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded p-3 text-sm text-blue-800 dark:text-blue-200">
                            <strong>{{ __('Tip:') }}</strong> {{ __('You can export a supplier as CSV or JSON, then import it back with edits for easy data management.') }}
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
