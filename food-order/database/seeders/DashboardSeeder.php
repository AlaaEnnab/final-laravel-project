<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;

class DashboardSeeder extends Seeder
{
    public function run()
    {
        // =====================
        // إنشاء Admin
        // =====================
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole('admin');

        // =====================
        // إنشاء بعض Vendors
        // =====================
        $vendors = [
            ['name'=>'Ali Vendor','email'=>'ali@example.com','phone'=>'0791234567','address'=>'Amman','active'=>1],
            ['name'=>'Sara Vendor','email'=>'sara@example.com','phone'=>'0789876543','address'=>'Irbid','active'=>1],
        ];

        foreach ($vendors as $v) {
            Vendor::create($v);
        }

        // =====================
        // إنشاء بعض Products لكل Vendor
        // =====================
        $allVendors = Vendor::all();
        foreach ($allVendors as $vendor) {
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
        // إنشاء بعض Orders
        // =====================
        $order1 = Order::create([
            'customer_name' => 'John Doe',
            'customer_phone' => '0777654321',
            'customer_address' => 'Amman',
            'total' => 9.98,
            'status' => 'pending',
        ]);

        $firstProduct = Product::first();
        $order1->items()->create([
            'product_id' => $firstProduct->id,
            'quantity' => 2,
            'subtotal' => $firstProduct->price * 2,
        ]);
    }
}
