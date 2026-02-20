<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\ImportExportService;
use Illuminate\Http\Request;

class ImportExportController extends Controller
{
    protected $importExportService;

    public function __construct(ImportExportService $importExportService)
    {
        $this->importExportService = $importExportService;
    }

    public function exportForm(Supplier $supplier)
    {
        return view('import-export.export', compact('supplier'));
    }

    public function export(Supplier $supplier, Request $request)
    {
        $format = $request->query('format', 'json');
        
        if ($format === 'csv') {
            return $this->importExportService->exportSupplierAsCSV($supplier);
        } else {
            // JSON format
            $data = $this->importExportService->exportSupplier($supplier);
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $filename = 'supplier_' . $supplier->id . '.json';
            
            return response($json, 200)
                ->header('Content-Type', 'application/json; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }
    }

    public function importForm()
    {
        $suppliers = Supplier::all();
        return view('import-export.import', compact('suppliers'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,csv',
            'supplier_id' => 'required|exists:suppliers,id',
            'conflict_strategy' => 'required|in:skip,overwrite,duplicate,reject'
        ]);

        try {
            $supplier = Supplier::findOrFail($request->supplier_id);
            $file = $request->file('file');
            
            $result = $this->importExportService->importSupplier(
                $supplier,
                $file,
                $request->conflict_strategy
            );

            if ($result['conflicts']) {
                return redirect()->route('import-export.conflict-review')
                    ->with('conflicts', $result['conflicts'])
                    ->with('import_result', $result);
            }

            return redirect()->route('suppliers.show', $supplier)
                ->with('success', 'Supplier berhasil diimport. ' . $result['message']);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function conflictReview()
    {
        $conflicts = session('conflicts', []);
        $importResult = session('import_result', []);
        
        if (empty($conflicts)) {
            return redirect()->route('import-export.import');
        }

        return view('import-export.conflict-review', compact('conflicts', 'importResult'));
    }

    public function resolveConflicts(Request $request)
    {
        $resolutions = $request->validate([
            'resolutions' => 'array'
        ]);

        // Process resolutions here
        // This would depend on your conflict handling logic
        
        return redirect()->route('suppliers.index')->with('success', 'Konflik berhasil diselesaikan');
    }
}
