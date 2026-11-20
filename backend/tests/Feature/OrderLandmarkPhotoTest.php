<?php

namespace Tests\Feature;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrderLandmarkPhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_single_photo_is_exposed_as_array(): void
    {
        $order = $this->makeOrder(['landmark_photo' => 'landmarks/patokan-1.jpg']);

        $this->assertSame(['landmarks/patokan-1.jpg'], $order->landmark_photo);
        $this->assertSame(['landmarks/patokan-1.jpg'], $order->landmark_photos);
        $this->assertCount(1, $order->landmark_photo_urls);
    }

    public function test_json_pair_of_photos_is_exposed_as_array(): void
    {
        $paths = ['landmarks/patokan-1.jpg', 'landmarks/patokan-2.jpg'];

        $order = $this->makeOrder(['landmark_photo' => json_encode($paths)]);

        $this->assertSame($paths, $order->landmark_photo);
        $this->assertSame($paths, $order->landmark_photos);
        $this->assertCount(2, $order->landmark_photo_urls);
    }

    public function test_one_photo_is_saved_as_plain_string_and_two_as_json(): void
    {
        $order = $this->makeOrder();

        $order->landmark_photo = ['landmarks/patokan-1.jpg'];
        $order->save();

        $this->assertSame('landmarks/patokan-1.jpg', $order->getRawOriginal('landmark_photo'));

        $order->landmark_photo = ['landmarks/patokan-1.jpg', 'landmarks/patokan-2.jpg'];
        $order->save();

        $this->assertSame(
            json_encode(['landmarks/patokan-1.jpg', 'landmarks/patokan-2.jpg']),
            $order->getRawOriginal('landmark_photo'),
        );
    }

    public function test_admin_order_page_renders_both_landmark_photos(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('landmarks/patokan-1.jpg', 'photo-one');
        Storage::disk('public')->put('landmarks/patokan-2.jpg', 'photo-two');

        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->makeOrder([
            'landmark_photo' => json_encode(['landmarks/patokan-1.jpg', 'landmarks/patokan-2.jpg']),
        ]);

        $response = $this->actingAs($admin)->get(
            OrderResource::getUrl('edit', ['record' => $order]),
        );

        $response->assertOk()
            ->assertSee('Foto Patokan Lokasi')
            ->assertSee('hingga 2 foto patokan')
            ->assertSee('patokan-1.jpg')
            ->assertSee('patokan-2.jpg');
    }

    private function makeOrder(array $overrides = []): Order
    {
        $user = User::factory()->create();

        return Order::create(array_merge([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $user->id,
            'total_amount' => 150000,
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'grand_total' => 150000,
            'shipping_address' => 'Jl. Melati No. 1, Kota Bandung',
            'recipient_name' => 'Siti Nurhaliza',
            'recipient_phone' => '081298765432',
        ], $overrides));
    }
}
