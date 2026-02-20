<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CltLayupController;
use App\Http\Controllers\CltLayerController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Supplier Routes
    Route::resource('suppliers', SupplierController::class);

    // Nested Routes: Suppliers -> Layups
    Route::resource('suppliers.layups', CltLayupController::class);

    // Nested Routes: Suppliers -> Layups -> Layers
    Route::resource('suppliers.layups.layers', CltLayerController::class);

    // Import/Export Routes
    Route::get('import-export/export/{supplier}', [ImportExportController::class, 'exportForm'])->name('import-export.export-form');
    Route::get('import-export/export-data/{supplier}', [ImportExportController::class, 'export'])->name('import-export.export');
    Route::get('import-export/import', [ImportExportController::class, 'importForm'])->name('import-export.import');
    Route::post('import-export/import', [ImportExportController::class, 'import'])->name('import-export.import-action');
    Route::get('import-export/conflict-review', [ImportExportController::class, 'conflictReview'])->name('import-export.conflict-review');
    Route::post('import-export/resolve-conflicts', [ImportExportController::class, 'resolveConflicts'])->name('import-export.resolve-conflicts');
});

require __DIR__.'/auth.php';
