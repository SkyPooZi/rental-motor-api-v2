<?php

namespace Database\Seeders;

use App\Models\MotorcycleList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MotorcycleListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $motorcycles = [
            ["image" => "images/crf.png", "name" => "CRF", "type" => "Sport", "brand" => "Honda", "stock" => 1, "price" => 200000],
            ["image" => "images/xsr.png", "name" => "XSR", "type" => "Sport", "brand" => "Yamaha", "stock" => 2, "price" => 200000],
            ["image" => "images/vespa_sprint.png", "name" => "Vespa Sprint", "type" => "Premium Matic", "brand" => "Vespa", "stock" => 2, "price" => 200000],
            ["image" => "images/xmax.png", "name" => "XMAX", "type" => "Premium Matic", "brand" => "Yamaha", "stock" => 2, "price" => 200000],
            ["image" => "images/pcx.png", "name" => "PCX", "type" => "Premium Matic", "brand" => "Honda", "stock" => 2, "price" => 175000],
            ["image" => "images/nmax.png", "name" => "NMAX", "type" => "Premium Matic", "brand" => "Yamaha", "stock" => 2, "price" => 175000],
            ["image" => "images/vario_160.png", "name" => "Vario 160", "type" => "Matic", "brand" => "Honda", "stock" => 2, "price" => 160000],
            ["image" => "images/vario_150.png", "name" => "Vario 125/150", "type" => "Matic", "brand" => "Honda", "stock" => 2, "price" => 150000],
            ["image" => "images/scoopy_new.png", "name" => "Scoopy New", "type" => "Matic", "brand" => "Honda", "stock" => 2, "price" => 150000],
            ["image" => "images/scoopy_fi.png", "name" => "Scoopy Fi", "type" => "Matic", "brand" => "Honda", "stock" => 2, "price" => 110000],
            ["image" => "images/beat.png", "name" => "Beat", "type" => "Matic", "brand" => "Honda", "stock" => 2, "price" => 125000],
            ["image" => "images/xeon.png", "name" => "Xeon", "type" => "Matic", "brand" => "Yamaha", "stock" => 2, "price" => 100000]
        ];

        foreach ($motorcycles as $motorcycle) {
            MotorcycleList::create([
                'image' => $motorcycle['image'],
                'name' => $motorcycle['name'],
                'type' => $motorcycle['type'],
                'brand' => $motorcycle['brand'],
                'stock' => $motorcycle['stock'],
                'price_per_day' => ($motorcycle['price'] * 0.25) + $motorcycle['price'],
                'price_per_week' => ($motorcycle['price'] * 6 * 0.25) + ($motorcycle['price'] * 6),
                'delivery_price' => $motorcycle['price'] * 0.1,
                'status' => 'Tersedia',
                'unavailable_start_date' => null,
                'unavailable_end_date' => null,
                'is_hidden' => false,
            ]);
        }
    }
}
