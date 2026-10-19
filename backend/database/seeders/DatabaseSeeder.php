<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gambar contoh memakai placeholder lokal di disk public (tanpa
        // koneksi internet). Tiap record dibuatkan salinan file sendiri
        // agar penghapusan satu record tidak memutus gambar record lain.
        $placeholder = function (string $directory, string $filename, string $source): string {
            $path = $directory.'/'.$filename;

            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, file_get_contents(public_path('images/'.$source)));
            }

            return $path;
        };

        // 1. Users (Admin and Customer)
        $admin = User::firstOrCreate(
            ['email' => 'admin@shopdress.com'],
            [
                'name' => 'Administrator ShopDress',
                'password' => Hash::make(env('SEED_ADMIN_PASSWORD', 'password')),
                'phone' => '081234567890',
                'address' => 'Jl. Fashion Boulevard No. 1, Jakarta Selatan',
                'role' => 'admin',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@shopdress.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make(env('SEED_CUSTOMER_PASSWORD', 'password')),
                'phone' => '081298765432',
                'address' => 'Jl. Melati Indah No. 45, Bandung, Jawa Barat',
                'role' => 'customer',
            ]
        );

        $customer2 = User::firstOrCreate(
            ['email' => 'anisa@shopdress.com'],
            [
                'name' => 'Anisa Rahma',
                'password' => Hash::make(env('SEED_CUSTOMER_PASSWORD', 'password')),
                'phone' => '081377889900',
                'address' => 'Jl. Diponegoro No. 12, Surabaya, Jawa Timur',
                'role' => 'customer',
            ]
        );

        // 2. Categories
        $categoriesData = [
            [
                'name' => 'Gamis & Abaya Modern',
                'slug' => 'gamis-abaya-modern',
                'description' => 'Koleksi busana muslimah gamis dan abaya anggun dengan bahan premium adem dan jatuh.',
                'image' => $placeholder('categories', 'placeholder-gamis-abaya-modern.svg', 'placeholder-category.svg'),
            ],
            [
                'name' => 'Casual & Daily Dress',
                'slug' => 'casual-daily-dress',
                'description' => 'Dress santai harian yang nyaman, stylish, dan cocok untuk hangout bersama teman.',
                'image' => $placeholder('categories', 'placeholder-casual-daily-dress.svg', 'placeholder-category.svg'),
            ],
            [
                'name' => 'Korean Style Dress',
                'slug' => 'korean-style-dress',
                'description' => 'Tren gaun ala Korea dengan potongan manis, ruffle, dan floral vintage look.',
                'image' => $placeholder('categories', 'placeholder-korean-style-dress.svg', 'placeholder-category.svg'),
            ],
            [
                'name' => 'Maxi & Long Dress',
                'slug' => 'maxi-long-dress',
                'description' => 'Gaun panjang anggun yang mempesona untuk acara semi-formal dan pesta santai.',
                'image' => $placeholder('categories', 'placeholder-maxi-long-dress.svg', 'placeholder-category.svg'),
            ],
            [
                'name' => 'Party & Formal Dress',
                'slug' => 'party-formal-dress',
                'description' => 'Gaun pesta mewah berkelas dengan aksen lace, payet elegan, dan satin sutra.',
                'image' => $placeholder('categories', 'placeholder-party-formal-dress.svg', 'placeholder-category.svg'),
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = Category::firstOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        // 3. Products
        $productsData = [
            [
                'category_slug' => 'gamis-abaya-modern',
                'name' => 'Aurelia Silk Gamis Dress',
                'slug' => 'aurelia-silk-gamis-dress',
                'description' => 'Gamis sutra satin premium dengan detail lengan lonceng dan tali pinggang fleksibel. Sangat jatuh, tidak menerawang, dan adem dipakai seharian.',
                'price' => 349000,
                'featured' => true,
                'images' => [
                    $placeholder('products', 'placeholder-aurelia-silk-gamis-dress.svg', 'placeholder-product.svg'),
                ],
                'variants' => [
                    ['size' => 'S', 'color' => 'Sage Green', 'stock' => 15, 'additional_price' => 0],
                    ['size' => 'M', 'color' => 'Sage Green', 'stock' => 20, 'additional_price' => 0],
                    ['size' => 'L', 'color' => 'Sage Green', 'stock' => 12, 'additional_price' => 0],
                    ['size' => 'M', 'color' => 'Mocca Nude', 'stock' => 18, 'additional_price' => 0],
                    ['size' => 'L', 'color' => 'Mocca Nude', 'stock' => 10, 'additional_price' => 0],
                    ['size' => 'XL', 'color' => 'Hitam Pekat', 'stock' => 8, 'additional_price' => 15000],
                ],
            ],
            [
                'category_slug' => 'korean-style-dress',
                'name' => 'Ji-Woo Floral Ruffle Midi Dress',
                'slug' => 'ji-woo-floral-ruffle-midi-dress',
                'description' => 'Dress bergaya retro Korea dengan motif bunga lembut, kerah v-neck aksen ruffle, dan karet pinggang ramping. Memberikan siluet tubuh yang proporsional dan manis.',
                'price' => 279000,
                'featured' => true,
                'images' => [
                    $placeholder('products', 'placeholder-ji-woo-floral-ruffle-midi-dress.svg', 'placeholder-product.svg'),
                ],
                'variants' => [
                    ['size' => 'S', 'color' => 'Dusty Pink', 'stock' => 25, 'additional_price' => 0],
                    ['size' => 'M', 'color' => 'Dusty Pink', 'stock' => 30, 'additional_price' => 0],
                    ['size' => 'L', 'color' => 'Dusty Pink', 'stock' => 15, 'additional_price' => 0],
                    ['size' => 'S', 'color' => 'Soft Cream', 'stock' => 20, 'additional_price' => 0],
                    ['size' => 'M', 'color' => 'Soft Cream', 'stock' => 22, 'additional_price' => 0],
                ],
            ],
            [
                'category_slug' => 'casual-daily-dress',
                'name' => 'Luna Linen Button-Down Shirt Dress',
                'slug' => 'luna-linen-button-down-shirt-dress',
                'description' => 'Kemeja dress berbahan linen rami organik yang breathable dan sejuk. Dilengkapi saku samping fungsional dan kancing kayu natural.',
                'price' => 225000,
                'featured' => true,
                'images' => [
                    $placeholder('products', 'placeholder-luna-linen-button-down-shirt-dress.svg', 'placeholder-product.svg'),
                ],
                'variants' => [
                    ['size' => 'All Size', 'color' => 'Terracotta', 'stock' => 35, 'additional_price' => 0],
                    ['size' => 'All Size', 'color' => 'Navy Blue', 'stock' => 28, 'additional_price' => 0],
                    ['size' => 'All Size', 'color' => 'Off White', 'stock' => 19, 'additional_price' => 0],
                ],
            ],
            [
                'category_slug' => 'party-formal-dress',
                'name' => 'Seraphina Velvet Evening Gown',
                'slug' => 'seraphina-velvet-evening-gown',
                'description' => 'Gaun malam berbahan velvet bludru mewah dengan belahan samping anggun dan siluet body-skimming. Pilihan sempurna untuk pesta gala dan jamuan formal.',
                'price' => 589000,
                'featured' => true,
                'images' => [
                    $placeholder('products', 'placeholder-seraphina-velvet-evening-gown.svg', 'placeholder-product.svg'),
                ],
                'variants' => [
                    ['size' => 'S', 'color' => 'Emerald Green', 'stock' => 8, 'additional_price' => 0],
                    ['size' => 'M', 'color' => 'Emerald Green', 'stock' => 10, 'additional_price' => 0],
                    ['size' => 'L', 'color' => 'Emerald Green', 'stock' => 6, 'additional_price' => 0],
                    ['size' => 'M', 'color' => 'Midnight Black', 'stock' => 14, 'additional_price' => 0],
                    ['size' => 'L', 'color' => 'Midnight Black', 'stock' => 12, 'additional_price' => 0],
                ],
            ],
            [
                'category_slug' => 'maxi-long-dress',
                'name' => 'Boho Tiered Chiffon Maxi Dress',
                'slug' => 'boho-tiered-chiffon-maxi-dress',
                'description' => 'Gaun maxi bertingkat dengan bahan sifon ceruti berfuring penuh. Memberikan efek melayang yang dramatis saat bergerak dan berfoto.',
                'price' => 310000,
                'featured' => false,
                'images' => [
                    $placeholder('products', 'placeholder-boho-tiered-chiffon-maxi-dress.svg', 'placeholder-product.svg'),
                ],
                'variants' => [
                    ['size' => 'S', 'color' => 'Caramel Beige', 'stock' => 12, 'additional_price' => 0],
                    ['size' => 'M', 'color' => 'Caramel Beige', 'stock' => 15, 'additional_price' => 0],
                    ['size' => 'L', 'color' => 'Caramel Beige', 'stock' => 9, 'additional_price' => 0],
                ],
            ],
        ];

        $createdProducts = [];

        foreach ($productsData as $prodData) {
            $category = $categories[$prodData['category_slug']];

            $product = Product::firstOrCreate(
                ['slug' => $prodData['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $prodData['name'],
                    'description' => $prodData['description'],
                    'price' => $prodData['price'],
                    'is_active' => true,
                    'featured' => $prodData['featured'],
                ]
            );

            // Images
            foreach ($prodData['images'] as $index => $imagePath) {
                ProductImage::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                    ],
                    [
                        'is_primary' => ($index === 0),
                    ]
                );
            }

            // Variants
            foreach ($prodData['variants'] as $v) {
                $sku = 'SD-' . strtoupper(substr($prodData['slug'], 0, 4)) . '-' . strtoupper(substr($v['color'], 0, 3)) . '-' . $v['size'];
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'sku' => $sku,
                    ],
                    [
                        'size' => $v['size'],
                        'color' => $v['color'],
                        'stock' => $v['stock'],
                        'additional_price' => $v['additional_price'],
                    ]
                );
            }

            $createdProducts[] = $product;
        }

        // 4. Coupons
        Coupon::firstOrCreate(
            ['code' => 'SHOPDRESSNEW'],
            [
                'discount_type' => 'percent',
                'discount_amount' => 20, // 20%
                'min_spend' => 150000,
                'expires_at' => now()->addMonths(3),
                'usage_limit' => 500,
                'used_count' => 14,
                'is_active' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'HEMAT50RB'],
            [
                'discount_type' => 'fixed',
                'discount_amount' => 50000,
                'min_spend' => 250000,
                'expires_at' => now()->addMonths(2),
                'usage_limit' => 200,
                'used_count' => 32,
                'is_active' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'GRATISONGKIR'],
            [
                'discount_type' => 'fixed',
                'discount_amount' => 20000,
                'min_spend' => 100000,
                'expires_at' => now()->addMonths(6),
                'usage_limit' => null,
                'used_count' => 78,
                'is_active' => true,
            ]
        );

        // 5. Sample Reviews
        Review::firstOrCreate(
            ['user_id' => $customer->id, 'product_id' => $createdProducts[0]->id],
            [
                'rating' => 5,
                'comment' => 'Bahan Aurelia Silk ini bagus banget! Lembut, dingin di kulit, dan warnanya persis seperti di foto. Pengiriman juga super kilat!',
            ]
        );

        Review::firstOrCreate(
            ['user_id' => $customer2->id, 'product_id' => $createdProducts[1]->id],
            [
                'rating' => 5,
                'comment' => 'Dress Ji-Woo ini lucu banget potongannya! Ruffle-nya rapi dan pas di badan. Suka sekali dengan packagingnya yang rapi.',
            ]
        );

        // 6. Sample Orders
        $firstProduct = $createdProducts[0];
        $variant1 = $firstProduct->variants->first();

        $sampleOrder = Order::firstOrCreate(
            ['order_number' => 'ORD-20241011-00001'],
            [
                'user_id' => $customer->id,
                'total_amount' => $firstProduct->price,
                'shipping_cost' => 20000,
                'discount_amount' => 0,
                'grand_total' => $firstProduct->price + 20000,
                'status' => OrderStatus::PAID,
                'payment_status' => PaymentStatus::SETTLED,
                'shipping_address' => $customer->address,
                'recipient_name' => $customer->name,
                'recipient_phone' => $customer->phone,
                'tracking_number' => 'JNE-SD8892110',
                'notes' => 'Tolong dibungkus plastik ekstra ya kak.',
            ]
        );

        if ($variant1) {
            OrderItem::firstOrCreate(
                ['order_id' => $sampleOrder->id, 'product_variant_id' => $variant1->id],
                [
                    'product_name' => $firstProduct->name,
                    'variant_details' => [
                        'sku' => $variant1->sku,
                        'size' => $variant1->size,
                        'color' => $variant1->color,
                    ],
                    'price' => $firstProduct->price,
                    'quantity' => 1,
                    'subtotal' => $firstProduct->price,
                ]
            );
        }
    }
}
