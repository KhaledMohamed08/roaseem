<?php

namespace Database\Seeders;

use App\Models\UnitStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitStatus::create([
            'name' => 'sale'
        ]);

        UnitStatus::create([
            'name' => 'rent'
        ]);
    }
}
