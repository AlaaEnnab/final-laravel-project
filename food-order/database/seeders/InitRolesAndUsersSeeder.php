<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InitRolesAndUsersSeeder extends Seeder
{
    public function run()
    {
        // =====================
      
        // =====================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

      
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();
        Role::query()->delete();
        Permission::query()->delete();
        User::query()->delete();
        Vendor::query()->delete();
        Product::query()->delete();
        Order::query()->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =====================
       
        // =====================
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $vendorRole = Role::firstOrCreate(['name' => 'vendor']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // =====================
      
        // =====================
        $permissions = [
            'add product',
            'delete product',
            'place order',
            'manage vendors',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // =====================
       
        // =====================
        $adminRole->givePermissionTo(Permission::all());
        $vendorRole->givePermissionTo(['add product', 'delete product']);
        $customerRole->givePermissionTo(['place order']);

        // =====================

        // =====================
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password123')]
        );
        $admin->assignRole('admin');

        $vendorUser = User::firstOrCreate(
            ['email' => 'vendor@example.com'],
            ['name' => 'Vendor User', 'password' => Hash::make('password123')]
        );
        $vendorUser->assignRole('vendor');

        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Customer User', 'password' => Hash::make('password123')]
        );
        $customer->assignRole('customer');

        // =====================
       
        // =====================
        $vendors = [
            ['name'=>'Ali Vendor','email'=>'ali@example.com','phone'=>'0791234567','address'=>'Amman','active'=>1],
            ['name'=>'Sara Vendor','email'=>'sara@example.com','phone'=>'0789876543','address'=>'Irbid','active'=>1],
        ];

        foreach ($vendors as $v) {
            Vendor::create($v);
        }

        // =====================
        
        // =====================
        foreach (Vendor::all() as $vendor) {
            Product::create([
                'name' => 'Pizza ' . $vendor->id,
                'price' => 5.99,
                'description' => 'Delicious cheese pizza',
                'vendor_id' => $vendor->id,
            ]);

            Product::create([
                'name' => 'Burger ' . $vendor->id,
                'price' => 3.99,
                'description' => 'Tasty beef burger',
                'vendor_id' => $vendor->id,
            ]);
        }

        // =====================
    
        // =====================
        $firstProduct = Product::first();
        if ($firstProduct) {
            $order1 = Order::create([
                'customer_name' => 'John Doe',
                'customer_phone' => '0777654321',
                'customer_address' => 'Amman',
                'total' => $firstProduct->price * 2,
                'status' => 'pending',
            ]);

            $order1->items()->create([
                'product_id' => $firstProduct->id,
                'quantity' => 2,
                'subtotal' => $firstProduct->price * 2,
            ]);
        }
    }
}
