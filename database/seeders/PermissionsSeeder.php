<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'add unit']);
        Permission::create(['name' => 'index unites']);
        Permission::create(['name' => 'edit unites']);
        Permission::create(['name' => 'index orders']);
        Permission::create(['name' => 'edit profile']);
        Permission::create(['name' => 'chat']);
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'index users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'add favourite']);
        Permission::create(['name' => 'show favourites']);
    }
}
