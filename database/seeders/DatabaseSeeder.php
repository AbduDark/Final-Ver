<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Store, Category, Product, Treasury};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء السوبر أدمن
        $superAdmin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@alhusseini.com',
            'password' => Hash::make('AlHusseini@2025'),
            'type' => 'super_admin',
            'store_id' => null,
            'email_verified_at' => now(),
        ]);

        // إنشاء متجر تجريبي
        $store = Store::create([
            'name' => 'فرع الرياض الرئيسي',
            'address' => 'شارع الملك فهد، الرياض',
            'phone' => '0112345678',
            'email' => 'riyadh@alhusseini.com',
            'super_admin_id' => $superAdmin->id,
            'status' => 'active',
        ]);

        // إنشاء خزينة للمتجر
        Treasury::create([
            'store_id' => $store->id,
            'current_balance' => 10000.00,
            'last_transaction_type' => 'income',
            'last_transaction_amount' => 10000.00,
            'last_transaction_date' => now(),
        ]);

        // إنشاء أدمن المتجر
        $storeAdmin = User::create([
            'name' => 'أحمد محمد',
            'email' => 'admin@riyadh.com',
            'password' => Hash::make('AlHusseini@2025'),
            'type' => 'admin',
            'store_id' => $store->id,
            'email_verified_at' => now(),
        ]);

        // إنشاء كاشير
        $cashier = User::create([
            'name' => 'فاطمة أحمد',
            'email' => 'cashier@riyadh.com',
            'password' => Hash::make('AlHusseini@2025'),
            'type' => 'cashier',
            'store_id' => $store->id,
            'email_verified_at' => now(),
        ]);

        // إنشاء فئات المنتجات
        $categoriesData = [
            ['name' => 'الهواتف الذكية', 'description' => 'جميع أنواع الهواتف الذكية'],
            ['name' => 'الإكسسوارات', 'description' => 'إكسسوارات الهواتف والأجهزة'],
            ['name' => 'أجهزة الكمبيوتر', 'description' => 'أجهزة الكمبيوتر واللابتوب'],
            ['name' => 'الألعاب', 'description' => 'ألعاب الفيديو والإكسسوارات'],
        ];

        $categories = [];
        foreach ($categoriesData as $categoryData) {
            $categories[] = Category::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'store_id' => $store->id,
            ]);
        }

        // إنشاء منتجات تجريبية
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'code' => 'IP15P001',
                'category_id' => $categories[0]->id,
                'purchase_price' => 3500.00,
                'sale_price' => 4200.00,
                'quantity' => 25,
                'min_quantity' => 5
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'code' => 'SGS24001',
                'category_id' => $categories[0]->id,
                'purchase_price' => 2800.00,
                'sale_price' => 3400.00,
                'quantity' => 30,
                'min_quantity' => 5
            ],
            [
                'name' => 'حافظة جلدية للهاتف',
                'code' => 'CASE001',
                'category_id' => $categories[1]->id,
                'purchase_price' => 25.00,
                'sale_price' => 45.00,
                'quantity' => 100,
                'min_quantity' => 20
            ],
            [
                'name' => 'سماعة بلوتوث',
                'code' => 'BT001',
                'category_id' => $categories[1]->id,
                'purchase_price' => 80.00,
                'sale_price' => 120.00,
                'quantity' => 3, // كمية قليلة لاختبار التنبيهات
                'min_quantity' => 5
            ],
            [
                'name' => 'لابتوب HP Pavilion',
                'code' => 'HP001',
                'category_id' => $categories[2]->id,
                'purchase_price' => 1800.00,
                'sale_price' => 2200.00,
                'quantity' => 15,
                'min_quantity' => 3
            ]
        ];

        foreach ($products as $productData) {
            Product::create(array_merge($productData, ['store_id' => $store->id]));
        }

        // إنشاء متجر ثاني لاختبار النظام
        $store2 = Store::create([
            'name' => 'فرع جدة',
            'address' => 'شارع الأمير محمد بن عبدالعزيز، جدة',
            'phone' => '0126789012',
            'email' => 'jeddah@alhusseini.com',
            'super_admin_id' => $superAdmin->id,
            'status' => 'active',
        ]);

        Treasury::create([
            'store_id' => $store2->id,
            'current_balance' => 8000.00,
            'last_transaction_type' => 'income',
            'last_transaction_amount' => 8000.00,
            'last_transaction_date' => now(),
        ]);

        // أدمن المتجر الثاني
        User::create([
            'name' => 'محمد علي',
            'email' => 'admin@jeddah.com',
            'password' => Hash::make('AlHusseini@2025'),
            'type' => 'admin',
            'store_id' => $store2->id,
            'email_verified_at' => now(),
        ]);
    }
}
