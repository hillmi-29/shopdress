<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddressBookTest extends TestCase
{
    use RefreshDatabase;

    private function addressPayload(array $overrides = []): array
    {
        return array_merge([
            'label' => 'Rumah',
            'recipient_name' => 'Siti Nurhaliza',
            'recipient_phone' => '081298765432',
            'province_code' => '32',
            'province_name' => 'Jawa Barat',
            'regency_code' => '3273',
            'regency_name' => 'Kota Bandung',
            'district_code' => '3273010',
            'district_name' => 'Sukajadi',
            'village_code' => '3273010001',
            'village_name' => 'Sukabungah',
            'postal_code' => '40162',
            'rt' => '001',
            'rw' => '002',
            'street' => 'Jl. Melati No. 1',
            'is_default' => false,
        ], $overrides);
    }

    private function makeAddress(User $user, array $overrides = []): Address
    {
        return Address::create(array_merge([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient_name' => $user->name,
            'recipient_phone' => '081200000000',
            'province_code' => '32',
            'province_name' => 'Jawa Barat',
            'regency_code' => '3273',
            'regency_name' => 'Kota Bandung',
            'district_code' => '3273010',
            'district_name' => 'Sukajadi',
            'village_code' => '3273010001',
            'village_name' => 'Sukabungah',
            'postal_code' => '40162',
            'rt' => '001',
            'rw' => '002',
            'street' => 'Jl. Melati No. 1',
            'is_default' => false,
        ], $overrides));
    }

    private function makeCart(User $user): ProductVariant
    {
        $category = Category::create(['name' => 'Kategori Alamat']);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Alamat',
            'description' => 'Produk untuk test alamat.',
            'price' => 100000,
            'is_active' => true,
            'featured' => false,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-ADDRESS-1',
            'size' => 'M',
            'color' => 'Sage Green',
            'stock' => 5,
            'additional_price' => 0,
        ]);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        return $variant;
    }

    public function test_profile_page_lists_saved_addresses(): void
    {
        $user = User::factory()->create();
        $this->makeAddress($user, ['label' => 'Rumah', 'is_default' => true]);
        $this->makeAddress($user, ['label' => 'Kantor', 'street' => 'Jl. Braga No. 10']);

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Alamat Pengiriman')
            ->assertSee('Rumah')
            ->assertSee('Kantor')
            ->assertSee('Jl. Melati No. 1')
            ->assertSee('Jl. Braga No. 10');
    }

    public function test_user_can_store_multiple_addresses_and_first_becomes_default(): void
    {
        $user = User::factory()->create();

        $first = $this->actingAs($user)->postJson(route('profile.addresses.store'), $this->addressPayload([
            'village_code' => '11.01.01.2001',
            'district_code' => '11.01.01',
            'regency_code' => '11.01',
        ]));
        $first->assertCreated();

        $second = $this->actingAs($user)->postJson(route('profile.addresses.store'), $this->addressPayload([
            'label' => 'Kantor',
            'street' => 'Jl. Asia Afrika No. 8',
        ]));
        $second->assertCreated();

        $this->assertSame(2, $user->addresses()->count());
        $this->assertTrue($user->addresses()->where('label', 'Rumah')->firstOrFail()->is_default);
        $this->assertFalse($user->addresses()->where('label', 'Kantor')->firstOrFail()->is_default);
    }

    public function test_setting_a_new_default_only_leaves_one_default_address(): void
    {
        $user = User::factory()->create();
        $home = $this->makeAddress($user, ['is_default' => true]);
        $office = $this->makeAddress($user, ['label' => 'Kantor', 'street' => 'Jl. Braga No. 10']);

        $this->actingAs($user)
            ->postJson(route('profile.addresses.default', $office))
            ->assertOk();

        $this->assertFalse($home->fresh()->is_default);
        $this->assertTrue($office->fresh()->is_default);
    }

    public function test_user_cannot_modify_or_delete_another_users_address(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $address = $this->makeAddress($owner, ['is_default' => true]);

        $this->actingAs($intruder)
            ->putJson(route('profile.addresses.update', $address), $this->addressPayload(['label' => 'Diubah']))
            ->assertForbidden();

        $this->actingAs($intruder)
            ->deleteJson(route('profile.addresses.destroy', $address))
            ->assertForbidden();

        $this->assertSame('Rumah', $address->fresh()->label);
    }

    public function test_deleting_default_address_promotes_another_address(): void
    {
        $user = User::factory()->create();
        $home = $this->makeAddress($user, ['is_default' => true]);
        $office = $this->makeAddress($user, ['label' => 'Kantor', 'street' => 'Jl. Braga No. 10']);

        $this->actingAs($user)
            ->deleteJson(route('profile.addresses.destroy', $home))
            ->assertOk();

        $this->assertSame(1, $user->addresses()->count());
        $this->assertTrue($office->fresh()->is_default);
    }

    public function test_checkout_page_shows_saved_addresses(): void
    {
        $user = User::factory()->create();
        $this->makeCart($user);
        $this->makeAddress($user, ['label' => 'Rumah Utama', 'is_default' => true]);

        $this->actingAs($user)
            ->get(route('checkout.index'))
            ->assertOk()
            ->assertSee('Alamat tersimpan')
            ->assertSee('Rumah Utama')
            ->assertSee('Jl. Melati No. 1')
            ->assertSee('Kelola alamat');
    }

    public function test_checkout_can_save_address_to_profile(): void
    {
        $user = User::factory()->create();
        $this->makeCart($user);

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')->once()->andReturn('snap-address-test');
        });

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'shipping_address' => 'Jl. Melati No. 1, RT 001/RW 002, Desa/Kel. Sukabungah, Kec. Sukajadi, Kota Bandung, Jawa Barat, 40162',
            'recipient_name' => 'Siti Nurhaliza',
            'recipient_phone' => '081298765432',
            'notes' => null,
            'save_address' => 1,
            'province_code' => '32',
            'province_name' => 'Jawa Barat',
            'regency_code' => '3273',
            'regency_name' => 'Kota Bandung',
            'district_code' => '3273010',
            'district_name' => 'Sukajadi',
            'village_code' => '3273010001',
            'village_name' => 'Sukabungah',
            'postal_code' => '40162',
            'rt' => '001',
            'rw' => '002',
            'street' => 'Jl. Melati No. 1',
        ]);

        $response->assertRedirect(route('checkout.payment', ['order' => Order::firstOrFail()->id]));

        $address = $user->addresses()->firstOrFail();
        $this->assertSame('Jl. Melati No. 1', $address->street);
        $this->assertSame('Kota Bandung', $address->regency_name);
        $this->assertTrue($address->is_default);
    }

    public function test_user_can_upload_and_delete_landmark_photo_on_address(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $photo = UploadedFile::fake()->image('patokan.jpg');
        $res = $this->actingAs($user)->post(route('profile.addresses.store'), [
            ...$this->addressPayload(),
            'landmark_photo' => $photo,
        ]);
        $res->assertCreated();

        $address = $user->addresses()->firstOrFail();
        $this->assertNotNull($address->landmark_photo);
        Storage::disk('public')->assertExists($address->landmark_photo);

        $updateRes = $this->actingAs($user)->put(route('profile.addresses.update', $address), [
            ...$this->addressPayload(),
            'remove_landmark_photo' => 1,
        ]);
        $updateRes->assertOk();

        $address->refresh();
        $this->assertNull($address->landmark_photo);
    }

    public function test_checkout_can_use_saved_landmark_photo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->makeCart($user);
        $savedAddress = $this->makeAddress($user, [
            'is_default' => true,
            'landmark_photo' => 'landmarks/test_patokan.jpg',
        ]);

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')->once()->andReturn('snap-landmark-test');
        });

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'shipping_address' => 'Jl. Melati No. 1, Kota Bandung, Jawa Barat',
            'recipient_name' => 'Siti Nurhaliza',
            'recipient_phone' => '081298765432',
            'saved_landmark_photo' => $savedAddress->landmark_photo,
        ]);

        $order = Order::firstOrFail();
        $response->assertRedirect(route('checkout.payment', ['order' => $order->id]));
        $this->assertSame(['landmarks/test_patokan.jpg'], $order->landmark_photos);
    }
}
