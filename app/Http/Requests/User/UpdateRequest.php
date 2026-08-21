<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Hapus input photo jika tidak ada file baru yang diunggah
     */
    protected function prepareForValidation()
    {
        if (!$this->hasFile('photo')) {
            $this->request->remove('photo');
        }
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = is_object($user) ? $user->id : $user;

        return [
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password'  => 'nullable|min:8',
            'role_id'   => 'required',
            'is_active' => 'nullable|boolean',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama Wajib diisi.',
            'name.max'          => 'Maksimal panjang nama 100 karakter.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan oleh user lain.',
            'password.min'      => 'Password minimal :min karakter.',
            'role_id.required'  => 'Role wajib diisi.',
            'photo.image'       => 'File yang diunggah harus berupa gambar.',
            'photo.mimes'       => 'Format gambar harus jpeg, png, jpg, gif, svg, atau webp.',
            'photo.max'         => 'Ukuran foto maksimal 2MB.',
        ];
    }
}