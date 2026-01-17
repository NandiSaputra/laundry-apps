<?php

namespace App\Http\Requests\Admin\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;

class StorePelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_pelanggan' => 'nullable|string|max:20|unique:pelanggans,kode_pelanggan',
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20|unique:pelanggans,telepon',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|unique:pelanggans,email',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama pelanggan wajib diisi.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'telepon.unique' => 'Nomor telepon ini sudah terdaftar sebagai pelanggan.',
            'kode_pelanggan.unique' => 'Kode pelanggan sudah digunakan.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
        ];
    }
}
