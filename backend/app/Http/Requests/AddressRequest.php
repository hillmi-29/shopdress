<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $rules = [
            'label' => ['nullable', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'province_code' => ['required', 'string', 'max:20'],
            'province_name' => ['required', 'string', 'max:100'],
            'regency_code' => ['required', 'string', 'max:20'],
            'regency_name' => ['required', 'string', 'max:100'],
            'district_code' => ['required', 'string', 'max:20'],
            'district_name' => ['required', 'string', 'max:100'],
            'village_code' => ['required', 'string', 'max:20'],
            'village_name' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'rt' => ['nullable', 'string', 'max:6'],
            'rw' => ['nullable', 'string', 'max:6'],
            'street' => ['required', 'string', 'max:500'],
            'existing_landmark_photos' => ['nullable'],
            'is_default' => ['sometimes', 'boolean'],
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
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'recipient_phone.required' => 'Nomor telepon penerima wajib diisi.',
            'province_code.required' => 'Provinsi wajib dipilih.',
            'regency_code.required' => 'Kota / kabupaten wajib dipilih.',
            'district_code.required' => 'Kecamatan wajib dipilih.',
            'village_code.required' => 'Kelurahan / desa wajib dipilih.',
            'postal_code.required' => 'Kode pos wajib diisi.',
            'street.required' => 'Detail jalan dan nomor rumah wajib diisi.',
        ];
    }
}
