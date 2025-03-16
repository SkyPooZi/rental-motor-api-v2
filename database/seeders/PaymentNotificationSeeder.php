<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\History;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\PaymentNotification;
use Faker\Factory as Faker;

class PaymentNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $userIds = User::pluck('id')->toArray();
        $historyIds = History::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            PaymentNotification::create([
                'user_id' => $faker->randomElement($userIds),
                'history_id' => $faker->randomElement($historyIds),
                'message' => $faker->sentence(10),
                'total_amount' => $faker->numberBetween(50000, 500000),
                'due_date' => Carbon::now()->addDays($faker->numberBetween(1, 7)),
                'is_hidden' => $faker->boolean(20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
