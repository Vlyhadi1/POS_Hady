<?php

namespace App\Policies;

use App\Models\ItemPenjualan;
use App\Models\User;

class ItemPenjualanPolicy
{
    public function update(User $user, ItemPenjualan $itempenjualan): bool
    {
        return $itempenjualan->penjualan?->user_id === $user->id
            || strtolower(optional($user->role)->name ?? '') === 'admin';
    }

    public function delete(User $user, ItemPenjualan $itempenjualan): bool
    {
        return $itempenjualan->penjualan?->user_id === $user->id
            || strtolower(optional($user->role)->name ?? '') === 'admin';
    }
}
