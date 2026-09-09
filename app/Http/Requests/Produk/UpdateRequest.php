<?php

namespace App\Http\Requests\Produk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $produk = $this->route('produk');
        $categoryRule = Rule::exists('categories', 'id');

        if ($produk && $produk->category_id) {
            $categoryRule = Rule::exists('categories', 'id');
        }

        return [
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', $categoryRule],
            'purchase_price' => ['required', 'integer', 'min:0'],
            'selling_price' => ['required', 'integer', 'min:0', 'gte:purchase_price'],
            'stock' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'minimum_stok' => ['nullable', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Jenis produk wajib dipilih.',
            'category_id.exists' => 'Jenis produk tidak valid.',
            'purchase_price.required' => 'Harga beli wajib diisi.',
            'purchase_price.integer' => 'Harga beli harus berupa angka.',
            'selling_price.required' => 'Harga jual wajib diisi.',
            'selling_price.integer' => 'Harga jual harus berupa angka.',
            'selling_price.gte' => 'Harga jual tidak boleh lebih kecil dari harga beli.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'satuan.required' => 'Satuan wajib dipilih.',
            'minimum_stok.integer' => 'Minimum stok harus berupa angka.',
        ];
    }
}
