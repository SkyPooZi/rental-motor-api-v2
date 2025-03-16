<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DiscountSeeder::class,
            ReviewSeeder::class,
            MotorcycleListSeeder::class,
            HistorySeeder::class,
            OtpSeeder::class,
            PaymentGatewaySeeder::class,
            NotificationSeeder::class,
            GoogleSeeder::class,
            FacebookSeeder::class,
            FinanceSeeder::class,
            ChangeLogSeeder::class,
            PaymentNotificationSeeder::class,
        ]);
    }
}
