<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Field; // Pastikan Model Field di-import

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data lapangan dummy
        $fields = [
            [
                'name' => 'Lapangan Standard A',
                'price_per_hour' => 50000,
                'description' => 'Lapangan karpet standar dengan penerangan LED yang cukup. Cocok untuk latihan santai.',
                // 'photo_url' => 'fields/standard_a.jpg', // Kosongkan dulu karena kita belum setup file storage
            ],
            [
                'name' => 'Lapangan Premium B',
                'price_per_hour' => 75000,
                'description' => 'Lapangan berstandar internasional dengan matras anti-slip dan AC yang nyaman. Ideal untuk turnamen.',
                // 'photo_url' => 'fields/premium_b.jpg',
            ],
            [
                'name' => 'Lapangan Economy C',
                'price_per_hour' => 35000,
                'description' => 'Lapangan beton dengan harga paling terjangkau. Penerangan cukup.',
                // 'photo_url' => 'fields/economy_c.jpg',
            ],
        ];

        foreach ($fields as $fieldData) {
            // Cek jika lapangan dengan nama ini sudah ada, agar tidak duplikat
            if (Field::where('name', $fieldData['name'])->doesntExist()) {
                 Field::create($fieldData);
            }
        }
        echo "Data lapangan dummy berhasil ditambahkan!\n";
    }
}