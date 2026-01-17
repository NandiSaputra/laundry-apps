<?php

namespace App\Http\Requests\Admin\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'items' => 'required|array|min:1',
            'items.*.layanan_id' => 'required|exists:layanans,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.catatan' => 'nullable|string',
            'diskon' => 'nullable|numeric|min:0',
            'jumlah_bayar' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    // Pre-fetch layanan to avoid N+1 query
                    $layananIds = collect($this->input('items', []))->pluck('layanan_id')->unique()->filter();
                    $layanans = \App\Models\Layanan::whereIn('id', $layananIds)->get()->keyBy('id');
                    
                    $subtotal = 0;
                    foreach ($this->input('items', []) as $item) {
                        $layanan = $layanans->get($item['layanan_id']);
                        if ($layanan) {
                            $subtotal += $layanan->harga * ($item['jumlah'] ?? 0);
                        }
                    }
                    $total = $subtotal - ($this->input('diskon') ?? 0);
                    
                    if ($value > 0 && $value < $total) {
                        $fail('Pembayaran harus lunas atau 0 (bayar nanti). Pembayaran parsial (DP) tidak diperbolehkan.');
                    }
                }
            ],
            'metode_pembayaran' => 'nullable|required_if:jumlah_bayar,>0|in:tunai,transfer,qris,ewallet',
            'catatan' => 'nullable|string',
            'parfum' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for Indonesian language.
     */
    public function messages(): array
    {
        return [
            'pelanggan_id.required' => 'Pelanggan harus dipilih.',
            'pelanggan_id.exists' => 'Pelanggan tidak valid.',
            'items.required' => 'Pilih setidaknya satu layanan.',
            'items.*.layanan_id.required' => 'Layanan tidak valid.',
            'items.*.jumlah.required' => 'Jumlah harus diisi.',
            'items.*.jumlah.min' => 'Jumlah minimal 0.01.',
        ];
    }
}
