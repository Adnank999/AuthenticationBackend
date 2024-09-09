<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create permissions with the 'api' guard
        Permission::create(['name' => 'create-users', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-users', 'guard_name' => 'api']);
        Permission::create(['name' => 'view-profile', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-users', 'guard_name' => 'api']);
    }
}
