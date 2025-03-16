<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Review;
use App\Models\History;
use App\Models\Discount;
use Faker\Factory as Faker;
use App\Models\MotorcycleList;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $userIds = User::pluck('id')->toArray();
        $motorcycleListIds = MotorcycleList::pluck('id')->toArray();
        $discountIds = Discount::pluck('id')->toArray();
        $reviewIds = Review::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            $daysAgo = $faker->randomElement([0, 3, 7]);
            $tanggalMulai = Carbon::now()->subDays($daysAgo)->format('Y-m-d H:i:s');
            $tanggalSelesai = Carbon::parse($tanggalMulai)->addYear()->format('Y-m-d H:i:s');

            History::create([
                'user_id' => $faker->randomElement($userIds),
                'full_name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => substr($faker->randomElement(['+62', '08']) . $faker->numerify('##########'), 0, 15),
                'social_media_account' => implode(', ', $faker->words(3)),
                'address' => $faker->city,
                'renter' => $faker->randomElement(['Diri Sendiri', 'Orang Lain']),
                'motorcycle_id' => $faker->randomElement($motorcycleListIds),
                'start_date' => $tanggalMulai,
                'duration' => $faker->numberBetween(1, 365),
                'end_date' => $tanggalSelesai,
                'renter_purpose' => $faker->sentence(5),
                'motorcycle_receipt' => $faker->randomElement(['Diambil', 'Diantar']),
                'emergency_contact_name' => $faker->name,
                'emergency_contact_number' => substr($faker->randomElement(['+62', '08']) . $faker->numerify('##########'), 0, 15),
                'emergency_contact_relationship' => $faker->randomElement(['Saudara', 'Teman', 'Orang Tua']),
                'point' => $faker->numberBetween(10000, 20000),
                'discount_id' => $faker->randomElement($discountIds),
                'payment_method' => $faker->randomElement(['Tunai', 'Non-Tunai']),
                'total_payment' => $faker->numberBetween(500000, 2000000),
                'history_status' => $faker->randomElement(['Menunggu Pembayaran', 'Dipesan', 'Sedang Digunakan', 'Selesai']),
                'review_id' => $faker->randomElement($reviewIds),
                'cancellation_date' => optional($faker->optional(0.2)->dateTimeThisYear())->format('Y-m-d'),
                'cancellation_reason' => $faker->optional(0.2)->sentence(6),
            ]);
        }
    }
}
