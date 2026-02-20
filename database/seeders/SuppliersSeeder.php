<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test suppliers with layups and layers
        $supplier1 = Supplier::factory()
            ->has(
                CltLayup::factory()
                    ->state(['name' => 'Standard Layup'])
                    ->has(
                        CltLayer::factory(3)
                            ->sequence(
                                ['layer_order' => 1, 'thickness' => 10.5, 'width' => 100, 'angle' => 0],
                                ['layer_order' => 2, 'thickness' => 12.0, 'width' => 100, 'angle' => 45],
                                ['layer_order' => 3, 'thickness' => 10.5, 'width' => 100, 'angle' => 90],
                            ),
                        'layers'
                    ),
                'layups'
            )
            ->state(['name' => 'Supplier A'])
            ->create();

        $supplier2 = Supplier::factory()
            ->has(
                CltLayup::factory(2)
                    ->has(
                        CltLayer::factory(2),
                        'layers'
                    ),
                'layups'
            )
            ->state(['name' => 'Supplier B'])
            ->create();
    }
}
