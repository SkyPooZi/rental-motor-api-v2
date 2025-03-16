<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Facebook;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FacebookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $userIds = User::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            Facebook::create([
                'access_token' => $faker->uuid,
                'user_id' => $faker->randomElement($userIds),
                'login_date' => $faker->dateTimeBetween('-1 month', 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
