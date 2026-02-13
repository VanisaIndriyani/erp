<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Customer;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Items (5 Data - Sparepart Motor)
        $items = [
            [
                'kode_item' => 'OLI-MPX1-08',
                'nama_item' => 'Oli MPX 1 0.8L (Bebek)',
                'jenis' => 'Oli',
                'merk' => 'AHM Oil',
                'satuan' => 'Botol',
                'harga_pokok' => 45000,
                'up_persen' => 22.22,
                'harga_jual' => 55000,
                'stok' => 50,
                'stok_minimum' => 10,
                'pajak_include' => true,
                'tipe_item' => 'inventory',
                'rak' => 'A-01',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
            [
                'kode_item' => 'BAN-IRC-7090-17',
                'nama_item' => 'Ban Luar IRC 70/90-17 (NR73)',
                'jenis' => 'Ban',
                'merk' => 'IRC',
                'satuan' => 'Pcs',
                'harga_pokok' => 180000,
                'up_persen' => 16.67,
                'harga_jual' => 210000,
                'stok' => 20,
                'stok_minimum' => 5,
                'pajak_include' => true,
                'tipe_item' => 'inventory',
                'rak' => 'B-01',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
            [
                'kode_item' => 'BUSI-NGK-C7HSA',
                'nama_item' => 'Busi NGK C7HSA (Grand/Supra)',
                'jenis' => 'Kelistrikan',
                'merk' => 'NGK',
                'satuan' => 'Pcs',
                'harga_pokok' => 12000,
                'up_persen' => 25,
                'harga_jual' => 15000,
                'stok' => 100,
                'stok_minimum' => 20,
                'pajak_include' => true,
                'tipe_item' => 'inventory',
                'rak' => 'C-01',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
            [
                'kode_item' => 'KAMPAS-REM-DPN-BEAT',
                'nama_item' => 'Kampas Rem Depan Beat/Vario',
                'jenis' => 'Pengereman',
                'merk' => 'AHM',
                'satuan' => 'Set',
                'harga_pokok' => 45000,
                'up_persen' => 33.33,
                'harga_jual' => 60000,
                'stok' => 30,
                'stok_minimum' => 5,
                'pajak_include' => true,
                'tipe_item' => 'inventory',
                'rak' => 'D-01',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
            [
                'kode_item' => 'RANTAI-KIT-SUPRA125',
                'nama_item' => 'Gear Set Supra X 125',
                'jenis' => 'Gear Set',
                'merk' => 'Indoparts',
                'satuan' => 'Set',
                'harga_pokok' => 140000,
                'up_persen' => 25,
                'harga_jual' => 175000,
                'stok' => 12,
                'stok_minimum' => 3,
                'pajak_include' => true,
                'tipe_item' => 'inventory',
                'rak' => 'E-01',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(
                ['kode_item' => $item['kode_item']],
                $item
            );
        }

        // 2. Seed Suppliers (5 Data)
        $suppliers = [
            [
                'kode' => 'SUP-001',
                'nama' => 'PT. ASTRA HONDA MOTOR',
                'alamat' => 'Jl. Laksda Yos Sudarso, Sunter I, Jakarta Utara',
                'jatuh_tempo' => 30,
                'menggunakan_pajak' => 'include',
                'nilai_pajak' => 11,
            ],
            [
                'kode' => 'SUP-002',
                'nama' => 'PT. YAMAHA INDONESIA MOTOR',
                'alamat' => 'Jl. DR. KRT. Radjiman Widyodiningrat, Jakarta Timur',
                'jatuh_tempo' => 30,
                'menggunakan_pajak' => 'include',
                'nilai_pajak' => 11,
            ],
            [
                'kode' => 'SUP-003',
                'nama' => 'CV. INDOPARTS UTAMA',
                'alamat' => 'Jl. Raya Bekasi Km. 20, Jakarta Timur',
                'jatuh_tempo' => 14,
                'menggunakan_pajak' => 'non',
                'nilai_pajak' => 0,
            ],
            [
                'kode' => 'SUP-004',
                'nama' => 'PT. FEDERAL KARYATAMA',
                'alamat' => 'Jl. Rawa Gelam I No. 9, Jakarta Timur',
                'jatuh_tempo' => 45,
                'menggunakan_pajak' => 'include',
                'nilai_pajak' => 11,
            ],
            [
                'kode' => 'SUP-005',
                'nama' => 'UD. MAJU JAYA MOTOR',
                'alamat' => 'Jl. Otista Raya No. 12, Jakarta Timur',
                'jatuh_tempo' => 0, // Cash
                'menggunakan_pajak' => 'non',
                'nilai_pajak' => 0,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['kode' => $supplier['kode']],
                $supplier
            );
        }

        // 3. Seed Customers (5 Data)
        $customers = [
            [
                'kode' => 'CUS-001',
                'nama' => 'Bengkel Berkah Motor',
                'alamat' => 'Jl. Raya Bogor Km. 25, Jakarta Timur',
                'jatuh_tempo' => 14,
            ],
            [
                'kode' => 'CUS-002',
                'nama' => 'Bengkel Maju Lancar',
                'alamat' => 'Jl. Dewi Sartika No. 10, Jakarta Timur',
                'jatuh_tempo' => 30,
            ],
            [
                'kode' => 'CUS-003',
                'nama' => 'Andi Saputra',
                'alamat' => 'Jl. Kalisari No. 5, Jakarta Timur',
                'jatuh_tempo' => 0, // Cash
            ],
            [
                'kode' => 'CUS-004',
                'nama' => 'CV. Trans Motor',
                'alamat' => 'Jl. Pemuda No. 88, Jakarta Timur',
                'jatuh_tempo' => 21,
            ],
            [
                'kode' => 'CUS-005',
                'nama' => 'Budi Hartono',
                'alamat' => 'Jl. Cipinang Muara No. 12, Jakarta Timur',
                'jatuh_tempo' => 7,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['kode' => $customer['kode']],
                $customer
            );
        }
    }
}
