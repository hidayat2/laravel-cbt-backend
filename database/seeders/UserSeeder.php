<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name'=> 'Hidayat',
            'email'    => 'hidayat.day233@gmail.com',
            'password' => Hash::make('12345678'),
            'roles'    => 'ADMIN',
            'phone'    => '08578453535',
          ]);
    }
}
