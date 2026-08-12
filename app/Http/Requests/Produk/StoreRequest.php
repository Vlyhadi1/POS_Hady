<?php

namespace App\Http\Requests\Produk;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        // Jika di HTML name="name", pakai ini. Jika name="nama", ubah jadi 'nama'
        'name'           => 'required|string|max:255',

        'category_id'    => 'required|exists:categories,id',

        // Jika di HTML name="purchase_price" / name="selling_price" / name="stock"
        'purchase_price' => 'required|integer|min:0',
        'selling_price'  => 'required|integer|min:0',
        'stock'          => 'required|integer|min:0',

        // PERBAIKAN: Ubah jadi nullable agar tidak wajib diisi dari form UI
        'satuan' => 'required|string|max:50',
        'minimum_stok'   => 'nullable|integer|min:0',

        'deskripsi'      => 'nullable|string',
        'status'         => 'nullable|boolean',
    ];
}

    public function messages(): array
    {
        return [

            'foto.image'                 => 'File harus berupa gambar.',
            'foto.mimes'                 => 'Format gambar harus JPG, JPEG, atau PNG.',
            'foto.max'                   => 'Ukuran gambar maksimal 2 MB.',

            'name.required'              => 'Nama produk wajib diisi.',

            'category_id.required'       => 'Jenis produk wajib dipilih.',
            'category_id.exists'         => 'Jenis produk tidak valid.',

            'purchase_price.required'    => 'Harga beli wajib diisi.',
            'purchase_price.integer'     => 'Harga beli harus berupa angka.',

            'selling_price.required'     => 'Harga jual wajib diisi.',
            'selling_price.integer'      => 'Harga jual harus berupa angka.',

            'stock.required'             => 'Stok wajib diisi.',
            'stock.integer'              => 'Stok harus berupa angka.',

            'satuan.required'            => 'Satuan wajib dipilih.',

            'minimum_stok.required'      => 'Minimum stok wajib diisi.',
            'minimum_stok.integer'       => 'Minimum stok harus berupa angka.',
        ];
    }
}