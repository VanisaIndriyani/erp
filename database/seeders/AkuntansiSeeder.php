<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AkuntansiAkun;

class AkuntansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akuns = [
            // ASSETS (HARTA)
            // 1. Kas & Bank
            [
                'kode_akun' => '1101',
                'nama_akun' => 'Kas Besar',
                'tipe' => 'asset',
                'saldo_normal' => 'debit',
            ],
            [
                'kode_akun' => '1102',
                'nama_akun' => 'Kas Kecil',
                'tipe' => 'asset',
                'saldo_normal' => 'debit',
            ],
            [
                'kode_akun' => '1103',
                'nama_akun' => 'Bank BCA',
                'tipe' => 'asset',
                'saldo_normal' => 'debit',
            ],
            [
                'kode_akun' => '1104',
                'nama_akun' => 'Bank Mandiri',
                'tipe' => 'asset',
                'saldo_normal' => 'debit',
            ],
            // 2. Piutang
            [
                'kode_akun' => '1105',
                'nama_akun' => 'Piutang Usaha',
                'tipe' => 'asset',
                'saldo_normal' => 'debit',
            ],
            // 3. Persediaan
            [
                'kode_akun' => '1106',
                'nama_akun' => 'Persediaan Barang Dagang',
                'tipe' => 'asset',
                'saldo_normal' => 'debit',
            ],
            
            // LIABILITIES (KEWAJIBAN)
            [
                'kode_akun' => '2101',
                'nama_akun' => 'Hutang Usaha',
                'tipe' => 'liability',
                'saldo_normal' => 'kredit',
            ],
            [
                'kode_akun' => '2102',
                'nama_akun' => 'Hutang Gaji',
                'tipe' => 'liability',
                'saldo_normal' => 'kredit',
            ],

            // EQUITY (MODAL)
            [
                'kode_akun' => '3101',
                'nama_akun' => 'Modal Pemilik',
                'tipe' => 'equity',
                'saldo_normal' => 'kredit',
            ],
            [
                'kode_akun' => '3102',
                'nama_akun' => 'Prive Pemilik',
                'tipe' => 'equity',
                'saldo_normal' => 'debit',
            ],
            [
                'kode_akun' => '3103',
                'nama_akun' => 'Laba Ditahan',
                'tipe' => 'equity',
                'saldo_normal' => 'kredit',
            ],

            // REVENUE (PENDAPATAN)
            [
                'kode_akun' => '4101',
                'nama_akun' => 'Penjualan Barang',
                'tipe' => 'revenue',
                'saldo_normal' => 'kredit',
            ],
            [
                'kode_akun' => '4102',
                'nama_akun' => 'Pendapatan Jasa',
                'tipe' => 'revenue',
                'saldo_normal' => 'kredit',
            ],
            [
                'kode_akun' => '4103',
                'nama_akun' => 'Diskon Penjualan',
                'tipe' => 'revenue',
                'saldo_normal' => 'debit',
            ],
            [
                'kode_akun' => '4104',
                'nama_akun' => 'Retur Penjualan',
                'tipe' => 'revenue',
                'saldo_normal' => 'debit',
            ],

            // EXPENSES (BEBAN)
            [
                'kode_akun' => '5101',
                'nama_akun' => 'Harga Pokok Penjualan (HPP)',
                'tipe' => 'expense',
                'saldo_normal' => 'debit',
            ],
            [
                'kode_akun' => '6101',
                'nama_akun' => 'Beban Gaji',
                'tipe' => 'expense',
                'saldo_normal' => 'debit',
            ],
            [
                'kode_akun' => '6102',
                'nama_akun' => 'Beban Listrik & Air',
                'tipe' => 'expense',
                'saldo_normal' => 'debit',
            ],
            [
                'kode_akun' => '6103',
                'nama_akun' => 'Beban Sewa',
                'tipe' => 'expense',
                'saldo_normal' => 'debit',
            ],
            [
                'kode_akun' => '6104',
                'nama_akun' => 'Beban Operasional Lainnya',
                'tipe' => 'expense',
                'saldo_normal' => 'debit',
            ],
        ];

        foreach ($akuns as $akun) {
            AkuntansiAkun::updateOrCreate(
                ['kode_akun' => $akun['kode_akun']], // Key to check
                $akun // Values to update/create
            );
        }
    }
}
