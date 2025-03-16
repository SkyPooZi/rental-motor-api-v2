<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discounts = [
            ["image" => "", "discount_name" => "Tidak Ada", "discount_price" => 0, "start_date" => now()->subDays(7)],
            ["image" => "images/diskon_awal_tahun.jpg", "discount_name" => "Diskon Awal Tahun", "discount_price" => 10, "start_date" => now()->subDays(3)],
            ["image" => "images/diskon_idul_fitri.jpg", "discount_name" => "Diskon Hari Raya Idul Fitri", "discount_price" => 15, "start_date" => now()],
            ["image" => "images/diskon_akhir_tahun.jpg", "discount_name" => "Diskon Akhir Tahun", "discount_price" => 20, "start_date" => now()->addDays(3)]
        ];

        foreach ($discounts as $discount) {
            Discount::create([
                'image' => $discount['image'],
                'discount_name' => $discount['discount_name'],
                'discount_price' => $discount['discount_price'],
                'start_date' => $discount['start_date'],
                'end_date' => Carbon::parse($discount['start_date'])->addYear(),
                'is_hidden' => false,
            ]);
        }
    }
}
