<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        if (!User::where('email', 'teste@px.com')->exists()) {
            User::create([
                'name' => 'Teste PX',
                'email' => 'teste@px.com',
                'password' => bcrypt('password'),
            ]);
        }
    }
}
