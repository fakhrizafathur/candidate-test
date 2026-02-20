<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ImportExportService
{
    /**
     * Export supplier with all related layups and layers
     */
    public function exportSupplier(Supplier $supplier): array
    {
        $supplier->load('layups.layers');

        return [
            'supplier' => [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'created_at' => $supplier->created_at,
            ],
            'layups' => $supplier->layups->map(function ($layup) {
                return [
                    'id' => $layup->id,
                    'name' => $layup->name,
                    'layers' => $layup->layers->map(function ($layer) {
                        return [
                            'id' => $layer->id,
                            'layer_order' => $layer->layer_order,
                            'thickness' => $layer->thickness,
                            'width' => $layer->width,
                            'angle' => $layer->angle,
                        ];
                    })->toArray(),
                ];
            })->toArray(),
        ];
    }

    /**
     * Import supplier data with conflict detection
     */
    public function importSupplier(Supplier $supplier, UploadedFile $file, string $strategy): array
    {
        $data = $this->parseFile($file);
        $conflicts = [];
        $imported = [
            'layups_created' => 0,
            'layups_updated' => 0,
            'layers_created' => 0,
            'layers_updated' => 0,
            'conflicts_found' => 0,
        ];

        if (!isset($data['layups'])) {
            throw new \Exception('File format tidak valid. Harus memiliki key "layups"');
        }

        DB::beginTransaction();

        try {
            foreach ($data['layups'] as $layupData) {
                $layupName = $layupData['name'] ?? null;
                
                if (!$layupName) {
                    continue;
                }

                // Check for layup conflict
                $existingLayup = $supplier->layups()
                    ->where('name', $layupName)
                    ->first();

                if ($existingLayup && $strategy === 'reject' && isset($layupData['layers'])) {
                    $imported['conflicts_found']++;
                    $conflicts[] = [
                        'type' => 'layup',
                        'message' => "Layup '{$layupName}' sudah ada",
                        'existing' => $existingLayup,
                        'incoming' => $layupData,
                    ];
                    continue;
                }

                // Create or get layup
                if (!$existingLayup) {
                    $layup = $supplier->layups()->create(['name' => $layupName]);
                    $imported['layups_created']++;
                } else {
                    $layup = $existingLayup;
                    $imported['layups_updated']++;
                }

                // Process layers
                if (isset($layupData['layers'])) {
                    foreach ($layupData['layers'] as $layerData) {
                        $layerOrder = $layerData['layer_order'] ?? null;
                        
                        if ($layerOrder === null) {
                            continue;
                        }

                        // Check for layer conflict
                        $existingLayer = $layup->layers()
                            ->where('layer_order', $layerOrder)
                            ->first();

                        if ($existingLayer) {
                            $hasConflict = $this->detectLayerConflict($existingLayer, $layerData);
                            
                            if ($hasConflict) {
                                $imported['conflicts_found']++;
                                $conflicts[] = [
                                    'type' => 'layer',
                                    'layup_id' => $layup->id,
                                    'layup_name' => $layup->name,
                                    'message' => "Layer dengan order {$layerOrder} memiliki perbedaan data",
                                    'existing' => $existingLayer->toArray(),
                                    'incoming' => $layerData,
                                ];

                                if ($strategy === 'reject') {
                                    continue;
                                } elseif ($strategy === 'skip') {
                                    continue;
                                } elseif ($strategy === 'overwrite') {
                                    $existingLayer->update($layerData);
                                    $imported['layers_updated']++;
                                    continue;
                                } elseif ($strategy === 'duplicate') {
                                    $layup->layers()->create($layerData);
                                    $imported['layers_created']++;
                                    continue;
                                }
                            }
                        } else {
                            // Create new layer
                            $layup->layers()->create($layerData);
                            $imported['layers_created']++;
                        }
                    }
                }
            }

            if ($strategy === 'reject' && !empty($conflicts)) {
                DB::rollBack();
                return [
                    'success' => false,
                    'conflicts' => $conflicts,
                    'message' => 'Import dibatalkan karena konflik ditemukan',
                ];
            }

            DB::commit();

            return [
                'success' => true,
                'conflicts' => $conflicts,
                'imported' => $imported,
                'message' => "Berhasil import: {$imported['layups_created']} layup dibuat, {$imported['layers_created']} layer dibuat",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Detect conflict between existing and incoming layer data
     */
    private function detectLayerConflict(CltLayer $existing, array $incoming): bool
    {
        $hasConflict = false;

        if ($existing->thickness != $incoming['thickness']) $hasConflict = true;
        if ($existing->width != $incoming['width']) $hasConflict = true;
        if ($existing->angle != $incoming['angle']) $hasConflict = true;

        return $hasConflict;
    }

    /**
     * Parse uploaded file (JSON, CSV, XLSX)
     */
    private function parseFile(UploadedFile $file): array
    {
        $extension = $file->getClientOriginalExtension();

        if ($extension === 'json') {
            return json_decode(file_get_contents($file->getRealPath()), true);
        } elseif ($extension === 'csv') {
            return $this->parseCSV($file);
        } elseif ($extension === 'xlsx') {
            return $this->parseExcel($file);
        } else {
            throw new \Exception('Format file tidak didukung. Gunakan JSON, CSV, atau XLSX.');
        }
    }

    /**
     * Parse CSV file
     */
    private function parseCSV(UploadedFile $file): array
    {
        $data = ['layups' => []];
        $csv = array_map('str_getcsv', file($file->getRealPath()));
        
        $header = array_shift($csv);
        
        foreach ($csv as $row) {
            if (count($row) < count($header)) {
                continue;
            }
            
            $row = array_combine($header, $row);
            
            $layupName = $row['layup_name'] ?? null;
            if (!$layupName) continue;

            $layupIndex = array_search($layupName, array_column($data['layups'], 'name'));
            
            if ($layupIndex === false) {
                $data['layups'][] = [
                    'name' => $layupName,
                    'layers' => [],
                ];
                $layupIndex = count($data['layups']) - 1;
            }

            if (isset($row['layer_order'])) {
                $data['layups'][$layupIndex]['layers'][] = [
                    'layer_order' => (int)$row['layer_order'],
                    'thickness' => (float)($row['thickness'] ?? 0),
                    'width' => (float)($row['width'] ?? 0),
                    'angle' => (int)($row['angle'] ?? 0),
                ];
            }
        }

        return $data;
    }

    /**
     * Parse Excel file
     */
    private function parseExcel(UploadedFile $file): array
    {
        // This requires PHPOffice/PhpSpreadsheet, which you may need to install
        // For now, we'll throw an exception
        throw new \Exception('Excel support requires additional package. Gunakan JSON atau CSV.');
    }

    /**
     * Export supplier as CSV
     */
    public function exportSupplierAsCSV(Supplier $supplier)
    {
        $supplier->load('layups.layers');
        
        $csv = "supplier_id,supplier_name,layup_id,layup_name,layer_id,layer_order,thickness,width,angle\n";
        
        foreach ($supplier->layups as $layup) {
            if (count($layup->layers) === 0) {
                // Supplier with layup but no layers
                $csv .= "{$supplier->id},{$supplier->name},{$layup->id},{$layup->name},,,,,\n";
            } else {
                foreach ($layup->layers as $layer) {
                    $csv .= "{$supplier->id},{$supplier->name},{$layup->id},{$layup->name},{$layer->id},{$layer->layer_order},{$layer->thickness},{$layer->width},{$layer->angle}\n";
                }
            }
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="supplier_' . $supplier->id . '.csv"');
    }

}
