<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Spareparts (SPR)
            [
                'kode_item' => 'SPR-001',
                'nama_item' => 'Filter Oli Hydraulic',
                'jenis' => 'Sparepart',
                'merk' => 'Caterpillar',
                'satuan' => 'Pcs',
                'harga_pokok' => 250000,
                'up_persen' => 20,
                'harga_jual' => 300000,
                'stok' => 100,
                'stok_minimum' => 10,
                'pajak_include' => true,
            ],
            [
                'kode_item' => 'SPR-002',
                'nama_item' => 'Bearing 6205 2RS',
                'jenis' => 'Sparepart',
                'merk' => 'SKF',
                'satuan' => 'Pcs',
                'harga_pokok' => 45000,
                'up_persen' => 30,
                'harga_jual' => 58500,
                'stok' => 200,
                'stok_minimum' => 20,
                'pajak_include' => true,
            ],
            [
                'kode_item' => 'SPR-003',
                'nama_item' => 'V-Belt B-52',
                'jenis' => 'Sparepart',
                'merk' => 'Mitsuboshi',
                'satuan' => 'Pcs',
                'harga_pokok' => 85000,
                'up_persen' => 25,
                'harga_jual' => 106250,
                'stok' => 50,
                'stok_minimum' => 5,
                'pajak_include' => false,
            ],
            [
                'kode_item' => 'SPR-004',
                'nama_item' => 'Brake Pad Set',
                'jenis' => 'Sparepart',
                'merk' => 'Bendix',
                'satuan' => 'Set',
                'harga_pokok' => 350000,
                'up_persen' => 15,
                'harga_jual' => 402500,
                'stok' => 30,
                'stok_minimum' => 5,
                'pajak_include' => true,
            ],
            [
                'kode_item' => 'SPR-005',
                'nama_item' => 'Seal Kit Cylinder Boom',
                'jenis' => 'Sparepart',
                'merk' => 'Komatsu',
                'satuan' => 'Set',
                'harga_pokok' => 1200000,
                'up_persen' => 10,
                'harga_jual' => 1320000,
                'stok' => 15,
                'stok_minimum' => 2,
                'pajak_include' => false,
            ],
            
            // Hoses (HSE)
            [
                'kode_item' => 'HSE-001',
                'nama_item' => 'Hydraulic Hose 1/2" R2',
                'jenis' => 'Hose',
                'merk' => 'Bridgestone',
                'satuan' => 'Meter',
                'harga_pokok' => 150000,
                'up_persen' => 30,
                'harga_jual' => 195000,
                'stok' => 500,
                'stok_minimum' => 50,
                'pajak_include' => true,
            ],
            [
                'kode_item' => 'HSE-002',
                'nama_item' => 'Fitting Hose 1/2" NPT Male',
                'jenis' => 'Hose',
                'merk' => 'Parker',
                'satuan' => 'Pcs',
                'harga_pokok' => 35000,
                'up_persen' => 40,
                'harga_jual' => 49000,
                'stok' => 300,
                'stok_minimum' => 30,
                'pajak_include' => true,
            ],
            [
                'kode_item' => 'HSE-003',
                'nama_item' => 'Industrial Hose 1" Water',
                'jenis' => 'Hose',
                'merk' => 'Toyox',
                'satuan' => 'Meter',
                'harga_pokok' => 80000,
                'up_persen' => 25,
                'harga_jual' => 100000,
                'stok' => 200,
                'stok_minimum' => 20,
                'pajak_include' => false,
            ],
            
            // Office Electronics (ELE) - Supplementing previous
            [
                'kode_item' => 'ELE-006', // Continuing logic from ITM but using ELE for variety
                'nama_item' => 'SSD Samsung 1TB Evo',
                'jenis' => 'Elektronik',
                'merk' => 'Samsung',
                'satuan' => 'Unit',
                'harga_pokok' => 1800000,
                'up_persen' => 10,
                'harga_jual' => 1980000,
                'stok' => 20,
                'stok_minimum' => 3,
                'pajak_include' => true,
            ],
            [
                'kode_item' => 'ELE-007',
                'nama_item' => 'RAM Corsair 16GB DDR4',
                'jenis' => 'Elektronik',
                'merk' => 'Corsair',
                'satuan' => 'Pcs',
                'harga_pokok' => 1200000,
                'up_persen' => 15,
                'harga_jual' => 1380000,
                'stok' => 25,
                'stok_minimum' => 5,
                'pajak_include' => true,
            ],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(
                ['kode_item' => $item['kode_item']], // Check by kode_item
                $item
            );
        }
    }
}
