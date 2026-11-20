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
        $rules = [
            'shipping_address' => ['required', 'string', 'max:500'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
            'checkout_mode' => ['nullable', 'in:cart,buy_now'],
            'coupon_code' => ['nullable', 'string', 'exists:coupons,code'],
            'saved_landmark_photo' => ['nullable'],
            'save_address' => ['nullable', 'boolean'],
            'province_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:20'],
            'province_name' => ['required_if:save_address,1', 'nullable', 'string', 'max:100'],
            'regency_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:20'],
            'regency_name' => ['required_if:save_address,1', 'nullable', 'string', 'max:100'],
            'district_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:20'],
            'district_name' => ['required_if:save_address,1', 'nullable', 'string', 'max:100'],
            'village_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:20'],
            'village_name' => ['required_if:save_address,1', 'nullable', 'string', 'max:100'],
            'postal_code' => ['required_if:save_address,1', 'nullable', 'string', 'max:10'],
            'rt' => ['nullable', 'string', 'max:6'],
            'rw' => ['nullable', 'string', 'max:6'],
            'street' => ['required_if:save_address,1', 'nullable', 'string', 'max:500'],
        ];

        if ($this->hasFile('landmark_photo')) {
            if (is_array($this->file('landmark_photo'))) {
                $rules['landmark_photo'] = ['array', 'max:2'];
                $rules['landmark_photo.*'] = ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'];
            } else {
                $rules['landmark_photo'] = ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'];
            }
        } else {
            $rules['landmark_photo'] = ['nullable'];
        }

        return $rules;
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
