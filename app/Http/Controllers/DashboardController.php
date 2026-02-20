<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'total_suppliers' => Supplier::count(),
            'total_layups' => CltLayup::count(),
            'total_layers' => CltLayer::count(),
            'suppliers_with_data' => Supplier::has('layups')->count(),
        ];

        $recent_suppliers = Supplier::with('layups')
            ->withCount('layups')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($supplier) {
                $supplier->layups_layers_count = $supplier->layups->sum(function ($layup) {
                    return $layup->layers()->count();
                });
                return $supplier;
            });

        return view('dashboard', compact('statistics', 'recent_suppliers'));
    }
}

