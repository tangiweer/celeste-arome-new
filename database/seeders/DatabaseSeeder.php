<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update Admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // change to strong password in production
                'role' => 'admin',
            ]
        );

        // Create or update Customer user
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
        // Create categories
        $fragranceCat = \App\Models\Category::firstOrCreate([
            'name' => 'Fragrance',
            'slug' => 'fragrance',
        ]);

        // Products data
        $products = [
            [
                'name' => 'Chanel No. 5',
                'slug' => 'chanel-no5',
                'brand' => 'Chanel',
                'description' => 'Classic floral fragrance.',
                'price' => 120.00,
                'stock' => 10,
                'is_active' => true,
                'image' => 'chanel-no5.jpeg',
            ],
            [
                'name' => 'Dior Sauvage',
                'slug' => 'dior-sauvage',
                'brand' => 'Dior',
                'description' => 'Fresh spicy fragrance.',
                'price' => 110.00,
                'stock' => 15,
                'is_active' => true,
                'image' => 'dior-sauvage.jpeg',
            ],
            [
                'name' => 'Gucci Bloom',
                'slug' => 'gucci-bloom',
                'brand' => 'Gucci',
                'description' => 'White floral fragrance.',
                'price' => 105.00,
                'stock' => 12,
                'is_active' => true,
                'image' => 'gucci-bloom.jpeg',
            ],
            [
                'name' => 'Tom Ford Oud Wood',
                'slug' => 'tom-ford-oud-wood',
                'brand' => 'Tom Ford',
                'description' => 'Woody spicy fragrance.',
                'price' => 150.00,
                'stock' => 8,
                'is_active' => true,
                'image' => 'tom-ford-oud-wood.jpg',
            ],
            [
                'name' => 'Versace Eros',
                'slug' => 'versace-eros',
                'brand' => 'Versace',
                'description' => 'Aromatic fougere fragrance.',
                'price' => 95.00,
                'stock' => 20,
                'is_active' => true,
                'image' => 'versace-eros.jpeg',
            ],
            [
                'name' => 'YSL Libre',
                'slug' => 'ysl-libre',
                'brand' => 'Yves Saint Laurent',
                'description' => 'Floral lavender fragrance.',
                'price' => 115.00,
                'stock' => 14,
                'is_active' => true,
                'image' => 'ysl-libre.jpeg',
            ],
        ];

        foreach ($products as $prod) {
            $product = \App\Models\Product::updateOrCreate([
                'slug' => $prod['slug'],
            ], [
                'category_id' => $fragranceCat->id,
                'name' => $prod['name'],
                'brand' => $prod['brand'],
                'description' => $prod['description'],
                'price' => $prod['price'],
                'stock' => $prod['stock'],
                'is_active' => $prod['is_active'],
            ]);
            \App\Models\ProductImage::updateOrCreate([
                'product_id' => $product->id,
                'path' => 'products/' . $prod['image'],
            ], [
                'is_primary' => true,
            ]);
        }
    }
}
