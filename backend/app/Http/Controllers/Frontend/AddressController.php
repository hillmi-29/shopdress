<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Store a new shipping address for the authenticated customer.
     */
    public function store(AddressRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $makeDefault = $request->boolean('is_default') || ! $user->addresses()->exists();

        $address = DB::transaction(function () use ($user, $data, $makeDefault) {
            if ($makeDefault) {
                $user->addresses()->where('is_default', true)->update(['is_default' => false]);
            }

            return $user->addresses()->create([
                ...$data,
                'is_default' => $makeDefault,
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Alamat berhasil disimpan.',
            'data' => $address,
        ], 201);
    }

    /**
     * Update one of the authenticated customer's addresses.
     */
    public function update(AddressRequest $request, Address $address): JsonResponse
    {
        $this->authorizeAddress($request, $address);

        $data = $request->validated();
        $makeDefault = $request->boolean('is_default');

        DB::transaction(function () use ($request, $address, $data, $makeDefault) {
            if ($makeDefault) {
                $request->user()->addresses()
                    ->where('id', '!=', $address->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $address->update([
                ...$data,
                'is_default' => $makeDefault || $address->is_default,
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Alamat berhasil diperbarui.',
            'data' => $address->fresh(),
        ]);
    }

    /**
     * Delete an address and promote another one when the default was removed.
     */
    public function destroy(Request $request, Address $address): JsonResponse
    {
        $this->authorizeAddress($request, $address);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $request->user()->addresses()->latest('id')->first()?->update(['is_default' => true]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Alamat berhasil dihapus.',
        ]);
    }

    /**
     * Mark an address as the customer's main shipping address.
     */
    public function setDefault(Request $request, Address $address): JsonResponse
    {
        $this->authorizeAddress($request, $address);

        DB::transaction(function () use ($request, $address) {
            $request->user()->addresses()
                ->where('id', '!=', $address->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);

            $address->update(['is_default' => true]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Alamat utama berhasil diperbarui.',
            'data' => $address->fresh(),
        ]);
    }

    private function authorizeAddress(Request $request, Address $address): void
    {
        abort_unless($address->user_id === $request->user()->id, 403);
    }
}
