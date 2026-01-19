<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Category, Product, Table};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::updateOrCreate(['username' => 'admin'], [
            'name' => 'Admin',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('admin123'),
        ]);

        User::updateOrCreate(['username' => 'kasir'], [
            'name' => 'Kasir',
            'role' => 'kasir',
            'is_active' => true,
            'password' => Hash::make('kasir123'),
        ]);

        // Categories
        $makanan = Category::updateOrCreate(['name' => 'Makanan'], ['sort_order' => 1, 'is_active' => true]);
        $minuman = Category::updateOrCreate(['name' => 'Minuman'], ['sort_order' => 2, 'is_active' => true]);

        // Products (contoh)
        Product::updateOrCreate(['name' => 'Nasi Goreng'], [
            'category_id' => $makanan->id,
            'price' => 25000,
            'cost' => 14000,
            'stock_enabled' => false,
            'stock_qty' => 0,
            'is_active' => true,
        ]);

        Product::updateOrCreate(['name' => 'Es Teh'], [
            'category_id' => $minuman->id,
            'price' => 8000,
            'cost' => 2500,
            'stock_enabled' => false,
            'stock_qty' => 0,
            'is_active' => true,
        ]);

        // Tables
        for ($i = 1; $i <= 12; $i++) {
            $code = 'T' . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
            Table::updateOrCreate(['code' => $code], [
                'name' => 'Meja ' . $i,
                'is_active' => true,
            ]);
        }
    }
}
