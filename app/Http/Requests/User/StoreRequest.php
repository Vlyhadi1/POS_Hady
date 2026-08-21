<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_id'  => 'required|exists:roles,id',
            
            // TAMBAHKAN ATURAN UNTUK FOTO DI SINI
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama Wajib diisi.',
            'name.max'          => 'Maksimal panjang nama 100 karakter.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal :min karakter.',
            'role_id.required'  => 'Roles Wajib diisi.',
            'role_id.exists'    => 'Role yang dipilih tidak valid.',
            
            // PESAN ERROR UNTUK FOTO
            'photo.image'       => 'File yang diunggah harus berupa gambar.',
            'photo.mimes'       => 'Format gambar harus jpeg, png, jpg, gif, svg, atau webp.',
            'photo.max'         => 'Ukuran foto maksimal 2MB.',
        ];
    }
}