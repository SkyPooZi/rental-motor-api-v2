<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('passwordadmin123'),
            'phone_number' => '+6283838383838',
            'role' => 'admin',
        ]);

        for ($i = 0; $i <= 10; $i++) {
            User::create([
                'username' => $faker->userName(),
                'email' => "{$faker->userName()}@gmail.com",
                'password' => Hash::make("password123"),
            ]);
        }
    }
}
