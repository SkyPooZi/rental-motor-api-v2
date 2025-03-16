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
            ["gambar" => "", "nama_diskon" => "Tidak Ada", "potongan_harga" => 0, "tanggal_mulai" => now()->subDays(7)],
            ["gambar" => "images/diskon_awal_tahun.jpg", "nama_diskon" => "Diskon Awal Tahun", "potongan_harga" => 10, "tanggal_mulai" => now()->subDays(3)],
            ["gambar" => "images/diskon_idul_fitri.jpg", "nama_diskon" => "Diskon Hari Raya Idul Fitri", "potongan_harga" => 15, "tanggal_mulai" => now()],
            ["gambar" => "images/diskon_akhir_tahun.jpg", "nama_diskon" => "Diskon Akhir Tahun", "potongan_harga" => 20, "tanggal_mulai" => now()->addDays(3)]
        ];

        foreach ($discounts as $discount) {
            Discount::create([
                'gambar' => $discount['gambar'],
                'nama_diskon' => $discount['nama_diskon'],
                'potongan_harga' => $discount['potongan_harga'],
                'tanggal_mulai' => $discount['tanggal_mulai'],
                'tanggal_selesai' => Carbon::parse($discount['tanggal_mulai'])->addYear(),
                'is_hidden' => false,
            ]);
        }
    }
}
