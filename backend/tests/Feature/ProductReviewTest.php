<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_submit_review(): void
    {
        $product = $this->makeProduct();

        $this->post(route('products.reviews.store', $product->slug), [
            'rating' => 5,
            'comment' => 'Ulasan dari tamu.',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_customer_without_purchase_cannot_submit_review(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $this->actingAs($user)
            ->from(route('products.show', $product->slug))
            ->post(route('products.reviews.store', $product->slug), [
                'rating' => 5,
                'comment' => 'Saya belum membeli produk ini.',
            ])
            ->assertRedirect(route('products.show', $product->slug))
            ->assertSessionHasErrors('review');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_unpaid_order_does_not_qualify_for_review(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $this->makeOrder($user, $product, OrderStatus::PENDING, PaymentStatus::UNPAID);

        $this->actingAs($user)
            ->from(route('products.show', $product->slug))
            ->post(route('products.reviews.store', $product->slug), [
                'rating' => 4,
                'comment' => 'Pesanan belum dibayar.',
            ])
            ->assertSessionHasErrors('review');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_verified_buyer_can_submit_review_with_photos_and_video(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $product = $this->makeProduct();
        $this->makeOrder($user, $product);

        $this->actingAs($user)
            ->post(route('products.reviews.store', $product->slug), [
                'rating' => 5,
                'comment' => 'Bahan adem, jahitan rapi, dan warnanya sesuai foto.',
                'photos' => [
                    UploadedFile::fake()->image('depan.jpg', 800, 1000),
                    UploadedFile::fake()->image('detail.jpg', 800, 1000),
                ],
                'video' => UploadedFile::fake()->create('ulasan.mp4', 2048, 'video/mp4'),
            ])
            ->assertRedirect(route('products.show', $product->slug).'#ulasan')
            ->assertSessionHas('success')
            ->assertSessionHas('review_submitted', true);

        $review = Review::firstOrFail();

        $this->assertSame($user->id, $review->user_id);
        $this->assertSame($product->id, $review->product_id);
        $this->assertSame(5, $review->rating);
        $this->assertSame('Bahan adem, jahitan rapi, dan warnanya sesuai foto.', $review->comment);
        $this->assertCount(2, $review->photos);
        $this->assertNotNull($review->video);

        Storage::disk('public')->assertExists($review->photos[0]);
        Storage::disk('public')->assertExists($review->photos[1]);
        Storage::disk('public')->assertExists($review->video);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('Bahan adem, jahitan rapi, dan warnanya sesuai foto.')
            ->assertSee($review->photo_urls[0])
            ->assertSee($review->video_url)
            ->assertSee('<video', false);
    }

    public function test_buyer_can_update_review_and_video_is_replaced(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $product = $this->makeProduct();
        $this->makeOrder($user, $product);

        Storage::disk('public')->put('reviews/photos/lama.jpg', 'foto lama');
        Storage::disk('public')->put('reviews/videos/lama.mp4', 'video lama');

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 3,
            'comment' => 'Ulasan awal.',
            'photos' => ['reviews/photos/lama.jpg'],
            'video' => 'reviews/videos/lama.mp4',
        ]);

        $oldVideo = $review->video;

        $this->actingAs($user)
            ->post(route('products.reviews.store', $product->slug), [
                'rating' => 5,
                'comment' => 'Ulasan yang sudah diperbarui.',
                'video' => UploadedFile::fake()->create('baru.mp4', 1024, 'video/mp4'),
            ])
            ->assertRedirect(route('products.show', $product->slug).'#ulasan')
            ->assertSessionHas('success');

        $review->refresh();

        $this->assertSame(5, $review->rating);
        $this->assertSame('Ulasan yang sudah diperbarui.', $review->comment);
        $this->assertSame(['reviews/photos/lama.jpg'], $review->photos);
        $this->assertNotSame($oldVideo, $review->video);

        Storage::disk('public')->assertMissing($oldVideo);
        Storage::disk('public')->assertExists($review->video);
        Storage::disk('public')->assertExists('reviews/photos/lama.jpg');
    }

    public function test_review_requires_rating_and_valid_media(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();
        $this->makeOrder($user, $product);

        $response = $this->actingAs($user)
            ->from(route('products.show', $product->slug))
            ->post(route('products.reviews.store', $product->slug), [
                'comment' => 'Tanpa rating.',
                'photos' => [UploadedFile::fake()->create('test.txt', 100, 'text/plain')],
            ]);

        $response->assertStatus(302); // redirect back with errors
        $response->assertSessionHasErrors(['rating', 'photos.0']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_review_form_is_only_shown_to_verified_buyers(): void
    {
        $product = $this->makeProduct();

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('Ingin menulis ulasan?')
            ->assertDontSee('Kirim Ulasan');

        $nonBuyer = User::factory()->create();

        $this->actingAs($nonBuyer)
            ->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('Ulasan khusus pembeli')
            ->assertDontSee('Kirim Ulasan');

        $buyer = User::factory()->create();
        $this->makeOrder($buyer, $product);

        $this->actingAs($buyer)
            ->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('Bagikan pengalamanmu')
            ->assertSee('Kirim Ulasan')
            ->assertSee('name="photos[]"', false)
            ->assertSee('name="video"', false);
    }

    private function makeProduct(): Product
    {
        $category = Category::create([
            'name' => 'Gamis & Abaya Modern',
            'slug' => 'gamis-abaya-modern',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Aurelia Silk Gamis Dress',
            'slug' => 'aurelia-silk-gamis-dress',
            'description' => 'Gamis sutra satin premium yang adem dipakai seharian.',
            'price' => 349000,
            'is_active' => true,
            'featured' => false,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-REVIEW-001',
            'size' => 'M',
            'color' => 'Sage Green',
            'stock' => 10,
            'additional_price' => 0,
        ]);

        return $product->load('variants');
    }

    private function makeOrder(
        User $user,
        Product $product,
        OrderStatus $status = OrderStatus::PAID,
        PaymentStatus $paymentStatus = PaymentStatus::SETTLED,
    ): Order {
        $variant = $product->variants->firstOrFail();

        $order = Order::create([
            'order_number' => 'ORD-REVIEW-'.strtoupper(uniqid()),
            'user_id' => $user->id,
            'total_amount' => $product->price,
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'grand_total' => $product->price,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'shipping_address' => 'Jl. Pengujian No. 1',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'variant_details' => [
                'size' => $variant->size,
                'color' => $variant->color,
                'sku' => $variant->sku,
            ],
            'price' => $product->price,
            'quantity' => 1,
            'subtotal' => $product->price,
        ]);

        return $order;
    }
}
