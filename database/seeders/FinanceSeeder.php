<?php

namespace Database\Seeders;

use App\Models\Finance;
use App\Models\History;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $historyIds = History::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            $totalMotorcyclePrice = $faker->numberBetween(500000, 2000000);
            $totalOvertimeFee = $faker->optional(0.3)->numberBetween(50000, 200000) ?? 0;
            $totalDeliveryFee = $faker->optional(0.5)->numberBetween(20000, 100000) ?? 0;
            $totalPointDeduction = $faker->optional(0.2)->numberBetween(10000, 50000) ?? 0;
            $totalDiscountFee = $faker->numberBetween(10000, 500000);
            $totalAdminFee = $faker->numberBetween(5000, 50000);
            $totalRescheduleFee = $faker->optional(0.1)->numberBetween(25000, 100000);

            $totalPayment = ($totalMotorcyclePrice + $totalOvertimeFee + $totalDeliveryFee + $totalAdminFee) - ($totalPointDeduction + $totalDiscountFee);

            Finance::create([
                'history_id' => $faker->randomElement($historyIds),
                'total_motorcycle_price' => $totalMotorcyclePrice,
                'total_overtime_fee' => $totalOvertimeFee,
                'total_delivery_fee' => $totalDeliveryFee,
                'total_point_deduction' => $totalPointDeduction,
                'total_discount_fee' => $totalDiscountFee,
                'total_admin_fee' => $totalAdminFee,
                'total_reschedule_fee' => $totalRescheduleFee,
                'total_payment' => max($totalPayment, 0),
            ]);
        }
    }
}
