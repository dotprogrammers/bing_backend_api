<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'users.list',
            'users.view',
            'users.create',
            'users.edit',
            'users.update',
            'users.delete',
            'products.list',
            'products.create',
            'products.edit',
            'products.delete',
            'orders.list',
            'orders.manage'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $vendorRole = Role::create(['name' => 'vendor']);
        $customerRole = Role::create(['name' => 'customer']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());

        $vendorRole->givePermissionTo([
            'products.list',
            'products.create',
            'products.edit',
            'products.delete',
            'orders.list'
        ]);

        $customerRole->givePermissionTo([
            'users.view',
            'orders.list'
        ]);

        // Create users
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);

        $vendor = User::create([
            'name' => 'Vendor',
            'email' => 'vendor@vendor.com',
            'password' => bcrypt('12345678'),
        ]);

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@customer.com',
            'password' => bcrypt('12345678'),
        ]);

        // Assign roles to users
        $admin->assignRole($adminRole);
        $vendor->assignRole($vendorRole);
        $customer->assignRole($customerRole);
    }
}
