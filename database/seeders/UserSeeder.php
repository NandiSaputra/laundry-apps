<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@laundry.com'], [
            'nama' => 'Admin Laundry',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telepon' => '08123456789',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'kasir@laundry.com'], [
            'nama' => 'Kasir Laundry',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'telepon' => '08123456790',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'owner@laundry.com'], [
            'nama' => 'Owner Laundry',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'telepon' => '08123456791',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'produksi@laundry.com'], [
            'nama' => 'Staff Produksi',
            'password' => Hash::make('password'),
            'role' => 'produksi',
            'telepon' => '08123456792',
            'is_active' => true,
        ]);
    }
}

