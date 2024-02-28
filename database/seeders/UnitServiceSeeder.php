<?php

namespace Database\Seeders;

use App\Models\UnitService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitService::create([
            'name' => 'gas',
        ]);
    }
}
