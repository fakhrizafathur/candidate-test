<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $this->call([
        ]);

        Supplier::factory(5)->create()->each(function ($supplier) {

            CltLayup::factory(rand(2,4))->create([
                'supplier_id' => $supplier->id
            ])->each(function ($layup) {

                for ($i = 1; $i <= rand(3,5); $i++) {
                    CltLayer::factory()->create([
                        'layup_id' => $layup->id,
                        'layer_order' => $i
                    ]);
                }

            });

        });
    }
}
