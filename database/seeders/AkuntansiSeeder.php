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
           
        ];

        foreach ($akuns as $akun) {
            AkuntansiAkun::updateOrCreate(
                ['kode_akun' => $akun['kode_akun']], // Key to check
                $akun // Values to update/create
            );
        }
    }
}
