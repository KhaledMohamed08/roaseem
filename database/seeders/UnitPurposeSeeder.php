<?php

namespace Database\Seeders;

use App\Models\UnitPurpose;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitPurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitPurpose::create([
            'name' => 'residential'
        ]);

        UnitPurpose::create([
            'name' => 'commercial'
        ]);
    }
}
