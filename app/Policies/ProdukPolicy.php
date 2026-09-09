<?php

namespace App\Policies;

use App\Models\Produk;
use App\Models\User;

class ProdukPolicy
{

    public function viewAny(User $user): bool
    {
        return in_array(
            strtolower(optional($user->role)->name ?? ''),
            [
                'admin',
                'kasir'
            ],
            true
        );
    }


    public function view(User $user, Produk $produk): bool
    {
        return in_array(
            strtolower(optional($user->role)->name ?? ''),
            [
                'admin',
                'kasir'
            ],
            true
        );
    }


    public function create(User $user): bool
    {
        return in_array(
            strtolower(optional($user->role)->name ?? ''),
            [
                'admin',
                'kasir'
            ],
            true
        );
    }


    public function update(User $user, Produk $produk): bool
    {
        return strtolower(optional($user->role)->name ?? '') === 'admin';
    }


    public function delete(User $user, Produk $produk): bool
    {
        return strtolower(optional($user->role)->name ?? '') === 'admin';
    }

}