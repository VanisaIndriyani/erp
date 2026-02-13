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
        // 1. Seed Items (5 Data)
        $items = [
            [
                'kode_item' => 'ITM-001',
                'nama_item' => 'Laptop Asus ROG',
                'jenis' => 'Elektronik',
                'merk' => 'Asus',
                'satuan' => 'Unit',
                'harga_pokok' => 15000000,
                'up_persen' => 10,
                'harga_jual' => 16500000,
                'stok' => 10,
                'stok_minimum' => 2,
                'pajak_include' => true,
                'tipe_item' => 'inventory',
                'rak' => 'A-01',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
            [
                'kode_item' => 'ITM-002',
                'nama_item' => 'Mouse Logitech Wireless',
                'jenis' => 'Aksesoris',
                'merk' => 'Logitech',
                'satuan' => 'Pcs',
                'harga_pokok' => 150000,
                'up_persen' => 20,
                'harga_jual' => 180000,
                'stok' => 50,
                'stok_minimum' => 5,
                'pajak_include' => false,
                'tipe_item' => 'inventory',
                'rak' => 'A-02',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
            [
                'kode_item' => 'ITM-003',
                'nama_item' => 'Keyboard Mechanical Rexus',
                'jenis' => 'Aksesoris',
                'merk' => 'Rexus',
                'satuan' => 'Unit',
                'harga_pokok' => 450000,
                'up_persen' => 15,
                'harga_jual' => 517500,
                'stok' => 25,
                'stok_minimum' => 3,
                'pajak_include' => true,
                'tipe_item' => 'inventory',
                'rak' => 'B-01',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
            [
                'kode_item' => 'ITM-004',
                'nama_item' => 'Monitor Samsung 24 Inch',
                'jenis' => 'Elektronik',
                'merk' => 'Samsung',
                'satuan' => 'Unit',
                'harga_pokok' => 2000000,
                'up_persen' => 10,
                'harga_jual' => 2200000,
                'stok' => 15,
                'stok_minimum' => 2,
                'pajak_include' => true,
                'tipe_item' => 'inventory',
                'rak' => 'B-02',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
            [
                'kode_item' => 'ITM-005',
                'nama_item' => 'Printer Epson L3210',
                'jenis' => 'Elektronik',
                'merk' => 'Epson',
                'satuan' => 'Unit',
                'harga_pokok' => 2500000,
                'up_persen' => 12,
                'harga_jual' => 2800000,
                'stok' => 8,
                'stok_minimum' => 2,
                'pajak_include' => false,
                'tipe_item' => 'inventory',
                'rak' => 'C-01',
                'hpp_system' => 'FIFO',
                'pilihan_harga' => 'satu_harga',
                'status_jual' => true,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

        // 2. Seed Suppliers (5 Data)
        $suppliers = [
            [
                'kode' => 'SUP-001',
                'nama' => 'PT. SINAR JAYA ABADI',
                'alamat' => 'Jl. Industri No. 45, Kawasan Industri Pulogadung, Jakarta Timur',
                'jatuh_tempo' => 30,
                'menggunakan_pajak' => 'include',
                'nilai_pajak' => 11,
            ],
            [
                'kode' => 'SUP-002',
                'nama' => 'CV. MITRA TEKNIK',
                'alamat' => 'Jl. Raya Bogor Km. 28 No. 10, Jakarta Timur',
                'jatuh_tempo' => 14,
                'menggunakan_pajak' => 'non',
                'nilai_pajak' => 0,
            ],
            [
                'kode' => 'SUP-003',
                'nama' => 'UD. SUMBER REJEKI',
                'alamat' => 'Jl. Pasar Pagi No. 88, Jakarta Barat',
                'jatuh_tempo' => 0, // Cash
                'menggunakan_pajak' => 'non',
                'nilai_pajak' => 0,
            ],
            [
                'kode' => 'ASTEK',
                'nama' => 'PT. ASIAN TEKNIK KREASINDO',
                'alamat' => 'JL. GATOT SUBROTO NO.21 RT.03 RW.02 CIMONE KEC.KARAWACI, TANGERANG, BANTEN',
                'jatuh_tempo' => 60,
                'menggunakan_pajak' => 'include',
                'nilai_pajak' => 11,
            ],
            [
                'kode' => 'SUP-005',
                'nama' => 'PT. MEGAH PERKASA',
                'alamat' => 'Kawasan Industri Jababeka II, Jl. Industri Selatan Blok JJ No. 4, Bekasi',
                'jatuh_tempo' => 45,
                'menggunakan_pajak' => 'exclude',
                'nilai_pajak' => 11,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // 3. Seed Customers (5 Data)
        $customers = [
            [
                'kode' => 'CUS-001',
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Kebon Jeruk No. 10, Jakarta',
                'jatuh_tempo' => 14,
            ],
            [
                'kode' => 'CUS-002',
                'nama' => 'PT. Sinar Harapan',
                'alamat' => 'Gedung Menara Mulia Lt. 5, Jakarta',
                'jatuh_tempo' => 30,
            ],
            [
                'kode' => 'CUS-003',
                'nama' => 'CV. Kreatif Digital',
                'alamat' => 'Ruko Grand Wisata Blok AA, Bekasi',
                'jatuh_tempo' => 21,
            ],
            [
                'kode' => 'CUS-004',
                'nama' => 'Siti Aminah',
                'alamat' => 'Jl. Merpati No. 5, Depok',
                'jatuh_tempo' => 7,
            ],
            [
                'kode' => 'CUS-005',
                'nama' => 'Toko Berkah Abadi',
                'alamat' => 'Pasar Baru Blok C No. 12, Bogor',
                'jatuh_tempo' => 14,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
