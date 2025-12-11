<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'manage_users',
            'manage_products',
            'manage_categories',
            'view_orders',
            'manage_orders',
        ];

        foreach($permissions as $perm){
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'api'
            ]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $customer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'api']);

        $admin->syncPermissions(Permission::all());
        $customer->syncPermissions(['view_orders']);
    }
}
