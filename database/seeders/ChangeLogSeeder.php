<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\History;
use App\Models\ChangeLog;
use App\Models\MotorcycleList;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ChangeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $userIds = User::pluck('id')->toArray();
        $motorcycleIds = MotorcycleList::pluck('id')->toArray();
        $historyIds = History::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            ChangeLog::create([
                'user_id' => $faker->randomElement($userIds),
                'motorcycle_id' => $faker->randomElement($motorcycleIds),
                'history_id' => $faker->randomElement($historyIds),
                'previous_data' => json_encode([
                    'status' => 'Dipesan',
                    'total_pembayaran' => $faker->numberBetween(500000, 2000000),
                ]),
                'updated_data' => json_encode([
                    'status' => 'Sedang Digunakan',
                    'total_pembayaran' => $faker->numberBetween(500000, 2000000),
                ]),
                'changed_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
