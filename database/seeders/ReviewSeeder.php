<?php

namespace Database\Seeders;

use App\Models\Review;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            return;
        }

        foreach ($userIds as $userId) {
            Review::create([
                'image' => 'images/review' . $faker->numberBetween(1, 7) . '.jpg',
                'user_id' => $faker->randomElement($userId),
                'rating' => $faker->numberBetween(1, 5),
                'comment' => $faker->sentence(10),
            ]);
        }
    }
}
