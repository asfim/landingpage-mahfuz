<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create storage directory for product images
        $publicPath = storage_path('app/public/products');
        File::ensureDirectoryExists($publicPath);

        // Define premium Unsplash images to download
        $images = [
            'iphone.jpg' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&q=80',
            'galaxy.jpg' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=600&q=80',
            'applewatch.jpg' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=600&q=80',
            'galaxywatch.jpg' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80',
            'nike.jpg' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',
            'adidas.jpg' => 'https://images.unsplash.com/photo-1587563871167-1ee9c731aefb?w=600&q=80',
            'hoodie.jpg' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=600&q=80',
            'cookware.jpg' => 'https://images.unsplash.com/photo-1584269600464-37b1b58a9fe7?w=600&q=80',
            'vase.jpg' => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?w=600&q=80',
        ];

        // Download files with error protection
        foreach ($images as $filename => $url) {
            $filePath = $publicPath . '/' . $filename;
            if (!File::exists($filePath)) {
                try {
                    $ctx = stream_context_create([
                        'http' => [
                            'timeout' => 8,
                            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
                        ]
                    ]);
                    $content = @file_get_contents($url, false, $ctx);
                    if ($content !== false) {
                        File::put($filePath, $content);
                    }
                } catch (\Exception $e) {
                    // Ignore errors during download and use empty/no image file
                }
            }
        }

        // 2. Create Categories
        $cat1 = Category::updateOrCreate(
            ['name' => 'Smartphones & Gadgets'],
            [
                'description' => 'Latest flagship phones, smartwatches, and innovative tech accessories.',
                'is_active' => true,
                'is_trending' => true,
            ]
        );

        $cat2 = Category::updateOrCreate(
            ['name' => 'Fashion & Apparel'],
            [
                'description' => 'Premium sneakers, activewear, hoodies, and clothing accessories.',
                'is_active' => true,
                'is_trending' => true,
            ]
        );

        $cat3 = Category::updateOrCreate(
            ['name' => 'Home & Living'],
            [
                'description' => 'Beautiful ceramic home decors, kitchen cookware, and essentials.',
                'is_active' => true,
                'is_trending' => false,
            ]
        );

        // 3. Create SubCategories
        $sub1 = SubCategory::updateOrCreate(
            ['name' => 'Smartphones', 'category_id' => $cat1->id],
            ['slug' => 'smartphones', 'is_active' => true]
        );

        $sub2 = SubCategory::updateOrCreate(
            ['name' => 'Smartwatches', 'category_id' => $cat1->id],
            ['slug' => 'smartwatches', 'is_active' => true]
        );

        $sub3 = SubCategory::updateOrCreate(
            ['name' => "Men's Wear", 'category_id' => $cat2->id],
            ['slug' => 'mens-wear', 'is_active' => true]
        );

        $sub4 = SubCategory::updateOrCreate(
            ['name' => "Women's Wear", 'category_id' => $cat2->id],
            ['slug' => 'womens-wear', 'is_active' => true]
        );

        $sub5 = SubCategory::updateOrCreate(
            ['name' => 'Kitchenware', 'category_id' => $cat3->id],
            ['slug' => 'kitchenware', 'is_active' => true]
        );

        $sub6 = SubCategory::updateOrCreate(
            ['name' => 'Home Decor', 'category_id' => $cat3->id],
            ['slug' => 'home-decor', 'is_active' => true]
        );

        // 4. Create Brands
        $brand1 = Brand::updateOrCreate(
            ['name' => 'Apple'],
            ['description' => 'Premium high-end smartphones and smart wearables.', 'is_active' => true]
        );

        $brand2 = Brand::updateOrCreate(
            ['name' => 'Samsung'],
            ['description' => 'Innovative android smartphones, watches, and appliances.', 'is_active' => true]
        );

        $brand3 = Brand::updateOrCreate(
            ['name' => 'Nike'],
            ['description' => 'World-famous sports sneakers and activewear apparel.', 'is_active' => true]
        );

        $brand4 = Brand::updateOrCreate(
            ['name' => 'Adidas'],
            ['description' => 'Original athletic sneakers, footwear, and apparel.', 'is_active' => true]
        );

        $brand5 = Brand::updateOrCreate(
            ['name' => 'Generic'],
            ['description' => 'Various non-branded high quality essentials.', 'is_active' => true]
        );

        // 5. Create Products
        Product::updateOrCreate(
            ['name' => 'iPhone 15 Pro Max'],
            [
                'category_id' => $cat1->id,
                'sub_category_id' => $sub1->id,
                'brand_id' => $brand1->id,
                'buy_price' => 135000.00,
                'price' => 149000.00,
                'discount_type' => 'percent',
                'discount_value' => 5.00,
                'stock' => 15,
                'sales_count' => 0,
                'slug' => 'iphone-15-pro-max',
                'image' => File::exists($publicPath . '/iphone.jpg') ? 'products/iphone.jpg' : null,
                'is_active' => true,
                'is_featured' => true,
                'is_new_arrival' => true,
                'description' => 'Experience the power of titanium with Apple flagship iPhone 15 Pro Max. Powered by the A17 Pro chip and featuring a pro camera system with 5x telephoto optical zoom.',
                'specifications' => [
                    'Display' => '6.7-inch Super Retina XDR OLED',
                    'Processor' => 'Apple A17 Pro (3 nm)',
                    'RAM' => '8GB',
                    'Storage' => '256GB',
                    'Battery' => '4441 mAh',
                ]
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Galaxy S24 Ultra'],
            [
                'category_id' => $cat1->id,
                'sub_category_id' => $sub1->id,
                'brand_id' => $brand2->id,
                'buy_price' => 125000.00,
                'price' => 139000.00,
                'discount_type' => 'percent',
                'discount_value' => 8.00,
                'stock' => 12,
                'sales_count' => 0,
                'slug' => 'galaxy-s24-ultra',
                'image' => File::exists($publicPath . '/galaxy.jpg') ? 'products/galaxy.jpg' : null,
                'is_active' => true,
                'is_featured' => true,
                'is_new_arrival' => false,
                'description' => 'Unleash AI-powered creativity and productivity with the Samsung Galaxy S24 Ultra. Encased in a titanium frame, complete with built-in S-Pen and 200MP camera.',
                'specifications' => [
                    'Display' => '6.8-inch Dynamic AMOLED 2X',
                    'Processor' => 'Snapdragon 8 Gen 3 for Galaxy',
                    'RAM' => '12GB',
                    'Storage' => '512GB',
                    'Battery' => '5000 mAh',
                ]
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Apple Watch Series 9'],
            [
                'category_id' => $cat1->id,
                'sub_category_id' => $sub2->id,
                'brand_id' => $brand1->id,
                'buy_price' => 38000.00,
                'price' => 45000.00,
                'discount_type' => null,
                'discount_value' => 0.00,
                'stock' => 20,
                'sales_count' => 0,
                'slug' => 'apple-watch-series-9',
                'image' => File::exists($publicPath . '/applewatch.jpg') ? 'products/applewatch.jpg' : null,
                'is_active' => true,
                'is_featured' => false,
                'is_new_arrival' => true,
                'description' => 'Smarter, brighter, mightier. The Apple Watch Series 9 features the S9 SiP, double tap gesture control, and advanced health sensors for premium tracking.',
                'specifications' => [
                    'Size' => '45mm Alumium Case',
                    'Connectivity' => 'GPS Only',
                    'Processor' => 'Apple S9 SiP',
                    'Water Resistance' => '50m WR',
                ]
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Samsung Galaxy Watch 6'],
            [
                'category_id' => $cat1->id,
                'sub_category_id' => $sub2->id,
                'brand_id' => $brand2->id,
                'buy_price' => 28000.00,
                'price' => 32000.00,
                'discount_type' => 'fixed',
                'discount_value' => 2500.00,
                'stock' => 25,
                'sales_count' => 0,
                'slug' => 'samsung-galaxy-watch-6',
                'image' => File::exists($publicPath . '/galaxywatch.jpg') ? 'products/galaxywatch.jpg' : null,
                'is_active' => true,
                'is_featured' => false,
                'is_new_arrival' => false,
                'description' => 'Better health starts with better sleep. Get personalized sleep coaching and advanced body composition analysis on Samsung Galaxy Watch 6.',
                'specifications' => [
                    'Size' => '44mm Case',
                    'OS' => 'Wear OS Powered by Samsung',
                    'Display' => '1.5-inch Super AMOLED',
                ]
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Nike Air Max 90'],
            [
                'category_id' => $cat2->id,
                'sub_category_id' => $sub3->id,
                'brand_id' => $brand3->id,
                'buy_price' => 8500.00,
                'price' => 11900.00,
                'discount_type' => null,
                'discount_value' => 0.00,
                'stock' => 18,
                'sales_count' => 0,
                'slug' => 'nike-air-max-90',
                'image' => File::exists($publicPath . '/nike.jpg') ? 'products/nike.jpg' : null,
                'is_active' => true,
                'is_featured' => true,
                'is_new_arrival' => true,
                'description' => 'A legend in streetwear culture. The Nike Air Max 90 stays true to its OG running roots with a waffle outsole, stitched overlays, and classic TPU accents.',
                'specifications' => [
                    'Material' => 'Leather, Suede, and Mesh Upper',
                    'Cushioning' => 'Visible Air Max Sole Unit',
                    'Color' => 'Infrared White/Red',
                ]
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Adidas Ultraboost 1.0'],
            [
                'category_id' => $cat2->id,
                'sub_category_id' => $sub3->id,
                'brand_id' => $brand4->id,
                'buy_price' => 12500.00,
                'price' => 16500.00,
                'discount_type' => 'percent',
                'discount_value' => 15.00,
                'stock' => 22,
                'sales_count' => 0,
                'slug' => 'adidas-ultraboost-1-0',
                'image' => File::exists($publicPath . '/adidas.jpg') ? 'products/adidas.jpg' : null,
                'is_active' => true,
                'is_featured' => true,
                'is_new_arrival' => false,
                'description' => 'Experience supreme comfort with the Adidas Ultraboost 1.0. Engineered with a Primeknit upper that adapts to your foot, and a responsive Boost midsole.',
                'specifications' => [
                    'Material' => 'Adidas Primeknit Textile Upper',
                    'Midsole' => 'Responsive Boost Cushioning',
                    'Outsole' => 'Continental Rubber Outsole',
                ]
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Nike Sportswear Hoodie'],
            [
                'category_id' => $cat2->id,
                'sub_category_id' => $sub4->id,
                'brand_id' => $brand3->id,
                'buy_price' => 3800.00,
                'price' => 5900.00,
                'discount_type' => null,
                'discount_value' => 0.00,
                'stock' => 30,
                'sales_count' => 0,
                'slug' => 'nike-sportswear-hoodie',
                'image' => File::exists($publicPath . '/hoodie.jpg') ? 'products/hoodie.jpg' : null,
                'is_active' => true,
                'is_featured' => false,
                'is_new_arrival' => true,
                'description' => 'A cozy wardrobe essential. The Nike Sportswear Club Fleece hoodie combines soft brushed fleece with classic streetwear styling for everyday comfort.',
                'specifications' => [
                    'Fabric' => '80% Cotton, 20% Polyester',
                    'Pocket' => 'Kangaroo Pocket',
                    'Hood' => 'Drawstring Adjustable Hood',
                ]
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Premium Cookware Pot Set'],
            [
                'category_id' => $cat3->id,
                'sub_category_id' => $sub5->id,
                'brand_id' => $brand5->id,
                'buy_price' => 5500.00,
                'price' => 7800.00,
                'discount_type' => 'fixed',
                'discount_value' => 800.00,
                'stock' => 10,
                'sales_count' => 0,
                'slug' => 'premium-cookware-pot-set',
                'image' => File::exists($publicPath . '/cookware.jpg') ? 'products/cookware.jpg' : null,
                'is_active' => true,
                'is_featured' => false,
                'is_new_arrival' => false,
                'description' => 'Upgrade your kitchen setup. This 5-piece non-stick premium cookware pot set features tempered glass lids, ergonomic heat-resistant handles, and uniform heat distribution.',
                'specifications' => [
                    'Material' => 'Anodized Aluminum Non-Stick Coating',
                    'Lids' => 'Tempered Glass Lids',
                    'Pieces' => '5-Piece Set',
                ]
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Modern Ceramic Flower Vase'],
            [
                'category_id' => $cat3->id,
                'sub_category_id' => $sub6->id,
                'brand_id' => $brand5->id,
                'buy_price' => 1400.00,
                'price' => 2400.00,
                'discount_type' => null,
                'discount_value' => 0.00,
                'stock' => 40,
                'sales_count' => 0,
                'slug' => 'modern-ceramic-flower-vase',
                'image' => File::exists($publicPath . '/vase.jpg') ? 'products/vase.jpg' : null,
                'is_active' => true,
                'is_featured' => false,
                'is_new_arrival' => true,
                'description' => 'Bring minimalist elegance to your home decor. This handmade ceramic flower vase features a textured matte finish, perfect for dried or fresh bouquets.',
                'specifications' => [
                    'Material' => 'Handcrafted Textured Ceramic',
                    'Dimensions' => 'Height: 10 inches, Base: 4 inches',
                    'Finish' => 'Matte Cream',
                ]
            ]
        );
    }
}
