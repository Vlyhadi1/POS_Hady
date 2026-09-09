<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->firstOrFail();
        $kasir = Role::where('name', 'kasir')->firstOrFail();

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin POS HADI',
                'password' => 'password',
                'role_id' => $admin->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Kasir POS HADI',
                'password' => 'password',
                'role_id' => $kasir->id,
            ]
        );
    }
}
