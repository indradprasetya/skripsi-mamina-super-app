<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use Illuminate\Database\Seeder;

class FoodCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'nama_kategori' => 'Sumber Karbohidrat',
                'deskripsi' => 'Makanan pokok yang kaya akan karbohidrat',
            ],
            [
                'nama_kategori' => 'Sumber Protein Hewani',
                'deskripsi' => 'Makanan yang berasal dari hewan kaya protein',
            ],
            [
                'nama_kategori' => 'Sumber Protein Nabati',
                'deskripsi' => 'Makanan yang berasal dari tumbuhan kaya protein',
            ],
            [
                'nama_kategori' => 'Sayuran',
                'deskripsi' => 'Berbagai jenis sayuran kaya serat dan vitamin',
            ],
            [
                'nama_kategori' => 'Buah-buahan',
                'deskripsi' => 'Berbagai jenis buah kaya vitamin dan mineral',
            ],
            [
                'nama_kategori' => 'Susu dan Produk Olahan',
                'deskripsi' => 'Produk susu dan olahannya kaya kalsium',
            ],
        ];

        foreach ($categories as $category) {
            FoodCategory::create($category);
        }
    }
}
