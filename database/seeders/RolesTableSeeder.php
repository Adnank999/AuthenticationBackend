<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create roles with the 'api' guard
        Role::create(['name' => 'Admin', 'guard_name' => 'api']);
        Role::create(['name' => 'Manager', 'guard_name' => 'api']);
        Role::create(['name' => 'User', 'guard_name' => 'api']);
    }
}
