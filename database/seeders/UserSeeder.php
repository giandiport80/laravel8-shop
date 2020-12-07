<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ];

        if(!User::where('email', $adminUser['email'])->exists()){ // .. 1
            User::create($adminUser);
        }
    }
}










// DOKUMENTASI

// p: clue 1
// jika tidak ada user yg punya email admin@gmail.com
// maka buat datanya
