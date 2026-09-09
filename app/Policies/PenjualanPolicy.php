<?php

namespace App\Policies;

use App\Models\Penjualan;
use App\Models\User;

class PenjualanPolicy
{
    public function view(User $user, Penjualan $penjualan): bool
    {
        return strtolower(optional($user->role)->name ?? '') === 'admin'
            || $penjualan->user_id === $user->id;
    }

    public function update(User $user, Penjualan $penjualan): bool
    {
        return $penjualan->status === 'OPEN'
            && (strtolower(optional($user->role)->name ?? '') === 'admin' || $penjualan->user_id === $user->id);
    }

    public function delete(User $user, Penjualan $penjualan): bool
    {
        return $penjualan->status === 'OPEN'
            && (strtolower(optional($user->role)->name ?? '') === 'admin' || $penjualan->user_id === $user->id);
    }
}
