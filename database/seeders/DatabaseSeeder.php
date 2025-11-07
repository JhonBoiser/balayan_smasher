<?php

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@balayan-smashers.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '09066238257',
            'address' => 'Calzada, Ermita',
            'city' => 'Balayan',
            'province' => 'Batangas',
            'zipcode' => '4213',
            'email_verified_at' => now(),
        ]);

        // Create Test Customer
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '09123456789',
            'email_verified_at' => now(),
        ]);

        // Create Categories
        $badminton = Category::create([
            'name' => 'Badminton',
            'slug' => 'badminton',
            'description' => 'Complete badminton equipment - rackets, shuttlecocks, shoes, and accessories',
            'is_active' => true,
            'order' => 1,
        ]);

        $basketball = Category::create([
            'name' => 'Basketball',
            'slug' => 'basketball',
            'description' => 'Basketball equipment and gear',
            'is_active' => true,
            'order' => 2,
        ]);

        $volleyball = Category::create([
            'name' => 'Volleyball',
            'slug' => 'volleyball',
            'description' => 'Volleyball equipment and accessories',
            'is_active' => true,
            'order' => 3,
        ]);

        $accessories = Category::create([
            'name' => 'Accessories',
            'slug' => 'accessories',
            'description' => 'Sports accessories and training equipment',
            'is_active' => true,
            'order' => 4,
        ]);

        // Create Badminton Products
        Product::create([
            'category_id' => $badminton->id,
            'name' => 'Yonex Astrox 99 Pro Badminton Racket',
            'slug' => 'yonex-astrox-99-pro',
            'description' => 'Professional badminton racket with rotational generator system for maximum power',
            'specifications' => 'Weight: 88g, Flex: Stiff, Balance: Head Heavy, Grip: G5',
            'price' => 8500.00,
            'sale_price' => 7500.00,
            'sku' => 'YNX-AST99-PRO',
            'stock' => 15,
            'is_featured' => true,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $badminton->id,
            'name' => 'Li-Ning Aeronaut 9000 Racket',
            'slug' => 'li-ning-aeronaut-9000',
            'description' => 'Lightweight badminton racket for speed and control',
            'specifications' => 'Weight: 82g, Flex: Medium, Balance: Even Balance, Grip: S2',
            'price' => 6500.00,
            'sku' => 'LN-AERO9K',
            'stock' => 20,
            'is_featured' => true,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $badminton->id,
            'name' => 'Yonex Mavis 350 Shuttlecocks (Yellow)',
            'slug' => 'yonex-mavis-350',
            'description' => 'Nylon shuttlecocks for practice and recreational play',
            'specifications' => 'Material: Nylon, Speed: Medium, Quantity: 6 pieces per tube',
            'price' => 450.00,
            'sku' => 'YNX-MAV350-YL',
            'stock' => 50,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $badminton->id,
            'name' => 'Victor SH-A920 Badminton Shoes',
            'slug' => 'victor-sh-a920-shoes',
            'description' => 'Professional badminton shoes with excellent grip and stability',
            'specifications' => 'Available sizes: 39-44, Color: White/Blue, Technology: VSR, Breathing Mesh',
            'price' => 3500.00,
            'sale_price' => 2999.00,
            'sku' => 'VIC-SHA920-WB',
            'stock' => 30,
            'is_featured' => true,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $badminton->id,
            'name' => 'Yonex AC402EX Badminton Grip',
            'slug' => 'yonex-ac402ex-grip',
            'description' => 'Super Grap replacement grip for better control',
            'specifications' => 'Length: 1200mm, Thickness: 0.6mm, Material: Polyurethane',
            'price' => 250.00,
            'sku' => 'YNX-AC402EX',
            'stock' => 100,
            'is_active' => true,
        ]);

        // Create Basketball Products
        Product::create([
            'category_id' => $basketball->id,
            'name' => 'Spalding TF-1000 Legacy Basketball',
            'slug' => 'spalding-tf-1000',
            'description' => 'Official size and weight basketball with superior grip',
            'specifications' => 'Size: 7 (Official), Material: Composite Leather, Indoor Use',
            'price' => 3200.00,
            'sku' => 'SPL-TF1000',
            'stock' => 25,
            'is_featured' => true,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $basketball->id,
            'name' => 'Molten GG7X Basketball',
            'slug' => 'molten-gg7x',
            'description' => 'FIBA approved basketball for competitive play',
            'specifications' => 'Size: 7, Material: Premium Composite, Indoor/Outdoor',
            'price' => 2800.00,
            'sku' => 'MLT-GG7X',
            'stock' => 18,
            'is_active' => true,
        ]);

        // Create Volleyball Products
        Product::create([
            'category_id' => $volleyball->id,
            'name' => 'Mikasa MVA200 Volleyball',
            'slug' => 'mikasa-mva200',
            'description' => 'Official Olympic and FIVB game ball',
            'specifications' => 'Size: 5 (Official), Material: Premium Synthetic Leather, Indoor Use',
            'price' => 2500.00,
            'sku' => 'MKS-MVA200',
            'stock' => 20,
            'is_featured' => true,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $volleyball->id,
            'name' => 'Molten V5M5000 Volleyball',
            'slug' => 'molten-v5m5000',
            'description' => 'FIVB approved volleyball for international competition',
            'specifications' => 'Size: 5, Material: Microfiber Composite, Indoor Use',
            'price' => 2200.00,
            'sku' => 'MLT-V5M5000',
            'stock' => 15,
            'is_active' => true,
        ]);

        // Create Accessories
        Product::create([
            'category_id' => $accessories->id,
            'name' => 'Sports Water Bottle 1L',
            'slug' => 'sports-water-bottle-1l',
            'description' => 'Durable sports water bottle with leak-proof cap',
            'specifications' => 'Capacity: 1000ml, Material: BPA-Free Plastic, Colors: Various',
            'price' => 350.00,
            'sku' => 'ACC-WB-1L',
            'stock' => 60,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $accessories->id,
            'name' => 'Athletic Sports Towel',
            'slug' => 'athletic-sports-towel',
            'description' => 'Quick-dry microfiber sports towel',
            'specifications' => 'Size: 80cm x 40cm, Material: Microfiber, Highly Absorbent',
            'price' => 280.00,
            'sku' => 'ACC-TOWEL-MF',
            'stock' => 40,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $accessories->id,
            'name' => 'Knee Support Pads (Pair)',
            'slug' => 'knee-support-pads',
            'description' => 'Elastic knee support for injury prevention',
            'specifications' => 'Sizes: S, M, L, XL, Material: Neoprene, Compression Support',
            'price' => 450.00,
            'sku' => 'ACC-KNEE-SUP',
            'stock' => 35,
            'is_active' => true,
        ]);
    }
}
