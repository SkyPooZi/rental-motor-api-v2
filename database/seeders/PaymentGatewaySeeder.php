<?php

namespace Database\Seeders;

use App\Models\History;
use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $historyIds = History::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            PaymentGateway::create([
                'history_id' => $faker->randomElement($historyIds),
                'order_number' => $faker->unique()->randomNumber(8),
                'order_date' => $faker->date(),
                'payment_date' => $faker->date(),
                'payment_method' => $faker->randomElement(['Transfer Bank', 'E-Wallet', 'Kartu Kredit']),
                'payment_status' => $faker->randomElement(['Pending', 'Success', 'Failed']),
                'total_order' => $faker->numberBetween(50000, 500000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
