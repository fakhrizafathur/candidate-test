<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use Illuminate\Http\Request;

class CltLayupController extends Controller
{
    public function index(Supplier $supplier)
    {
        $layups = $supplier->layups()->with('layers')->paginate(10);
        return view('layups.index', compact('supplier', 'layups'));
    }

    public function create(Supplier $supplier)
    {
        return view('layups.create', compact('supplier'));
    }

    public function store(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255|unique:clt_layups,name,NULL,id,supplier_id,' . $supplier->id
        ]);

        $supplier->layups()->create($validated);
        return redirect()->route('suppliers.layups.index', $supplier)->with('success', 'Layup berhasil ditambahkan');
    }

    public function show(Supplier $supplier, CltLayup $layup)
    {
        $layup->load('layers');
        return view('layups.show', compact('supplier', 'layup'));
    }

    public function edit(Supplier $supplier, CltLayup $layup)
    {
        return view('layups.edit', compact('supplier', 'layup'));
    }

    public function update(Request $request, Supplier $supplier, CltLayup $layup)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255|unique:clt_layups,name,' . $layup->id . ',id,supplier_id,' . $supplier->id
        ]);

        $layup->update($validated);
        return redirect()->route('suppliers.layups.show', [$supplier, $layup])->with('success', 'Layup berhasil diperbarui');
    }

    public function destroy(Supplier $supplier, CltLayup $layup)
    {
        $layup->delete();
        return redirect()->route('suppliers.layups.index', $supplier)->with('success', 'Layup berhasil dihapus');
    }
}
