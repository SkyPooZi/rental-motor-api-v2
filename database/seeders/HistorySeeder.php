<?php

namespace Database\Seeders;

use App\Models\History;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            $daysAgo = $faker->randomElement([0, 3, 7]);
            $tanggalMulai = Carbon::now()->subDays($daysAgo)->format('Y-m-d H:i:s');
            $tanggalSelesai = Carbon::parse($tanggalMulai)->addYear()->format('Y-m-d H:i:s');

            History::create([
                'pengguna_id' => $faker->numberBetween(1, 10),
                'nama_lengkap' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'nomor_hp' => $faker->phoneNumber,
                'akun_sosmed' => implode(', ', $faker->words(3)), // Contoh: "ig, fb, yt"
                'alamat' => $faker->city,
                'penyewa' => $faker->randomElement(['Diri Sendiri', 'Orang Lain']),
                'motor_id' => $faker->numberBetween(1, 20),
                'tanggal_mulai' => $tanggalMulai,
                'durasi' => $faker->numberBetween(1, 365),
                'tanggal_selesai' => $tanggalSelesai,
                'keperluan_menyewa' => $faker->sentence(5),
                'penerimaan_motor' => $faker->randomElement(['Diambil', 'Diantar']),
                'nama_kontak_darurat' => $faker->name,
                'nomor_kontak_darurat' => $faker->phoneNumber,
                'hubungan_dengan_kontak_darurat' => $faker->randomElement(['Saudara', 'Teman', 'Orang Tua']),
                'diskon_id' => $faker->optional()->numberBetween(1, 5),
                'metode_pembayaran' => $faker->randomElement(['Tunai', 'Non-Tunai']),
                'total_pembayaran' => $faker->numberBetween(500000, 2000000),
                'status_history' => $faker->randomElement(['Menunggu Pembayaran', 'Dipesan', 'Sedang Digunakan', 'Selesai']),
                'ulasan_id' => $faker->optional()->numberBetween(1, 50),
                'tanggal_pembatalan' => $faker->optional(0.2)->dateTimeThisYear()->format('Y-m-d'),
                'alasan_pembatalan' => $faker->optional(0.2)->sentence(6),
            ]);
        }
    }
}
