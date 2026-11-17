<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'shipping_address' => ['required', 'string', 'max:500'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
            'coupon_code' => ['nullable', 'string', 'exists:coupons,code'],
            'landmark_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'save_address' => ['nullable', 'boolean'],
            'province_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:10'],
            'province_name' => ['required_if:save_address,1', 'nullable', 'string', 'max:100'],
            'regency_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:10'],
            'regency_name' => ['required_if:save_address,1', 'nullable', 'string', 'max:100'],
            'district_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:10'],
            'district_name' => ['required_if:save_address,1', 'nullable', 'string', 'max:100'],
            'village_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:10'],
            'village_name' => ['required_if:save_address,1', 'nullable', 'string', 'max:100'],
            'postal_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:10'],
            'rt' => ['nullable', 'string', 'max:6'],
            'rw' => ['nullable', 'string', 'max:6'],
            'street' => ['required_if:save_address,1', 'nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'recipient_phone.required' => 'Nomor telepon penerima wajib diisi.',
            'coupon_code.exists' => 'Kode kupon tidak valid.',
        ];
    }
}
