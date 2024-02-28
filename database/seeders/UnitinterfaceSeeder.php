<?php

namespace Database\Seeders;

use App\Models\UnitInterface;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitinterfaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitInterface::create([
            'name' => 'north',
        ]);
    }
}
