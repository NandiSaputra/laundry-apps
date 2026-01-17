<?php

namespace App\Http\Requests\Admin\Layanan;

use Illuminate\Foundation\Http\FormRequest;

class StoreLayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategori_layanans,id',
            'kode_layanan' => 'nullable|string|max:20|unique:layanans,kode_layanan',
            'nama' => 'required|string|max:255',
            'satuan' => 'required|in:kg,pcs,pasang,meter',
            'harga' => 'required|numeric|min:0',
            'estimasi_jam' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'kode_layanan.unique' => 'Kode layanan sudah digunakan.',
            'nama.required' => 'Nama layanan wajib diisi.',
            'satuan.required' => 'Satuan wajib dipilih.',
            'satuan.in' => 'Satuan tidak valid (kg, pcs, pasang, meter).',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'estimasi_jam.required' => 'Estimasi waktu wajib diisi.',
            'is_active.required' => 'Status aktif wajib dipilih.',
        ];
    }
}
