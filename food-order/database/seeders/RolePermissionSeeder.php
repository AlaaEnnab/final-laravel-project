<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // create roll
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $vendor = Role::firstOrCreate(['name' => 'vendor']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

        // create permission
        $permissions = [
            'add product',
            'delete product',
            'place order',
            'manage vendors',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // link permissions with roles
        $admin->givePermissionTo(Permission::all());
        $vendor->givePermissionTo(['add product', 'delete product']); 
        $customer->givePermissionTo(['place order']); 
    }
}
