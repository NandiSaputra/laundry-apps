<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'required|string',
            'shop_phone' => 'required|string|max:20',
            'shop_email' => 'nullable|email|max:255',
            'receipt_footer' => 'nullable|string',
            'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Landing Page Settings
            'landing_hero_title' => 'nullable|string|max:255',
            'landing_hero_description' => 'nullable|string',
            'landing_feature_title' => 'nullable|string|max:255',
            'landing_feature_description' => 'nullable|string',
            'shop_hours_weekday' => 'nullable|string|max:255',
            'shop_hours_weekend' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'shop_name.required' => 'Nama laundry wajib diisi.',
            'shop_address.required' => 'Alamat laundry wajib diisi.',
            'shop_phone.required' => 'Nomor telepon wajib diisi.',
            'shop_email.email' => 'Format email tidak valid.',
            'shop_logo.image' => 'File harus berupa gambar.',
            'shop_logo.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
