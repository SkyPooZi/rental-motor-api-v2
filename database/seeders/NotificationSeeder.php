<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\History;
use App\Models\Discount;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $userIds = User::pluck('id')->toArray();
        $discountIds = Discount::pluck('id')->toArray();
        $historyIds = History::pluck('id')->toArray();
        $statuses = ['Menunggu Pembayaran', 'Dipesan', 'Sedang Digunakan'];

        for ($i = 0; $i < 10; $i++) {
            Notification::create([
                'user_id' => $faker->randomElement($userIds),
                'discount_id' => $faker->optional()->randomElement($discountIds),
                'history_id' => $faker->optional()->randomElement($historyIds),
                'history_status' => $faker->optional()->randomElement($statuses),
                'message' => $faker->sentence(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
