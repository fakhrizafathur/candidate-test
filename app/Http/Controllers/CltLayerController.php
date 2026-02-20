<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Http\Request;

class CltLayerController extends Controller
{
    public function index(Supplier $supplier, CltLayup $layup)
    {
        $layers = $layup->layers()->orderBy('layer_order')->paginate(10);
        return view('layers.index', compact('supplier', 'layup', 'layers'));
    }

    public function create(Supplier $supplier, CltLayup $layup)
    {
        return view('layers.create', compact('supplier', 'layup'));
    }

    public function store(Request $request, Supplier $supplier, CltLayup $layup)
    {
        $validated = $request->validate([
            'layer_order' => 'required|integer|min:1',
            'thickness' => 'required|numeric|min:0.01',
            'width' => 'required|numeric|min:0.01',
            'angle' => 'required|in:0,45,90'
        ]);

        $layup->layers()->create($validated);
        return redirect()->route('suppliers.layups.layers.index', [$supplier, $layup])->with('success', 'Layer berhasil ditambahkan');
    }

    public function show(Supplier $supplier, CltLayup $layup, CltLayer $layer)
    {
        return view('layers.show', compact('supplier', 'layup', 'layer'));
    }

    public function edit(Supplier $supplier, CltLayup $layup, CltLayer $layer)
    {
        return view('layers.edit', compact('supplier', 'layup', 'layer'));
    }

    public function update(Request $request, Supplier $supplier, CltLayup $layup, CltLayer $layer)
    {
        $validated = $request->validate([
            'layer_order' => 'required|integer|min:1',
            'thickness' => 'required|numeric|min:0.01',
            'width' => 'required|numeric|min:0.01',
            'angle' => 'required|in:0,45,90'
        ]);

        $layer->update($validated);
        return redirect()->route('suppliers.layups.layers.show', [$supplier, $layup, $layer])->with('success', 'Layer berhasil diperbarui');
    }

    public function destroy(Supplier $supplier, CltLayup $layup, CltLayer $layer)
    {
        $layer->delete();
        return redirect()->route('suppliers.layups.layers.index', [$supplier, $layup])->with('success', 'Layer berhasil dihapus');
    }
}
