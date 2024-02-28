<?php

namespace Database\Seeders;

use App\Models\UnitPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitPayment::create([
            'name' => 'cash',
        ]);
    }
}
