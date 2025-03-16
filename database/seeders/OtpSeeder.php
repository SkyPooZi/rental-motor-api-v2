<?php

namespace Database\Seeders;

use App\Models\Otp;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            Otp::create([
                'email' => $faker->unique()->safeEmail,
                'otp' => $faker->numberBetween(10000, 99999),
                'expiration_date' => Carbon::now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
