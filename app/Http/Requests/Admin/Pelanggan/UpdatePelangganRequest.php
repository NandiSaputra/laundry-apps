<?php

namespace App\Http\Requests\Admin\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'kode_pelanggan' => 'required|string|max:20|unique:pelanggans,kode_pelanggan,' . $id,
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20|unique:pelanggans,telepon,' . $id,
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|unique:pelanggans,email,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama pelanggan wajib diisi.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'telepon.unique' => 'Nomor telepon ini sudah digunakan pelanggan lain.',
            'kode_pelanggan.unique' => 'Kode pelanggan sudah digunakan.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan pelanggan lain.',
        ];
    }
}
