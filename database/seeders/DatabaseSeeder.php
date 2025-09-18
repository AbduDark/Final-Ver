<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Store, Treasury};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // // مسح جميع البيانات الحالية
        // User::truncate();
        // Store::truncate();
        // Treasury::truncate();

        // إنشاء السوبر أدمن - أحمد السيسي
        $superAdmin = User::create([
            'name' => 'أحمد السيسي',
            'email' => 'elsisi@admin.com',
            'password' => Hash::make('565656'),
            'type' => 'super_admin',
            'store_id' => null,
            'email_verified_at' => now(),
        ]);

        // إنشاء متجر الحسيني - فرع الواسطي
        $store = Store::create([
            'name' => 'الحسيني - فرع الواسطي',
            'address' => 'الواسطي',
            'phone' => '',
            'email' => '',
            'super_admin_id' => $superAdmin->id,
            'status' => 'active',
        ]);

        // إنشاء خزينة للمتجر
        Treasury::create([
            'store_id' => $store->id,
            'current_balance' => 0.00,
            'last_transaction_type' => 'income',
            'last_transaction_amount' => 0.00,
            'last_transaction_date' => now(),
        ]);

        // إنشاء أدمن المتجر - Sayed7a3
        $storeAdmin = User::create([
            'name' => 'Sayed7a3',
            'email' => 'Sayed7a3@gmail.com',
            'password' => Hash::make('Sayed2511@'),
            'type' => 'admin',
            'store_id' => $store->id,
            'email_verified_at' => now(),
        ]);

        // إنشاء كاشير المتجر
        $cashier = User::create([
            'name' => 'كاشير الواسطي',
            'email' => 'cashier@cashier.com',
            'password' => Hash::make('12121212'),
            'type' => 'cashier',
            'store_id' => $store->id,
            'email_verified_at' => now(),
        ]);
    }
}
