<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\AkuntansiAkun;
use App\Models\ItemMasuk;

class ItemMasukTest extends TestCase
{
    // use RefreshDatabase; // Caution: verify if we want to wipe DB. Usually better to use transaction or manual cleanup if not using in-memory DB.
    // Given the environment, I will use manual cleanup or just create unique data.

    public function test_store_item_masuk_successfully()
    {
        // 1. Create User
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        // 2. Create Item
        $item = Item::create([
            'kode_item' => 'TEST-IM-' . rand(1000, 9999),
            'nama_item' => 'Test Item Masuk',
            'tipe_item' => 'inventory',
            'harga_pokok' => 10000,
            'harga_jual' => 15000,
            'stok' => 10,
            'up_persen' => 0,
            'hpp_system' => 'LIFO',
            'pilihan_harga' => 'satu_harga',
        ]);

        // 3. Create Akun
        $akun = AkuntansiAkun::first();
        if (!$akun) {
            $akun = AkuntansiAkun::create([
                'kode_akun' => '999.99',
                'nama_akun' => 'Test Akun',
                'tipe' => 'expense',
                'saldo_normal' => 'debit',
            ]);
        }

        // 4. Prepare Data
        $no_transaksi = 'IM-TEST-' . rand(1000, 9999);
        $data = [
            'no_transaksi' => $no_transaksi,
            'tanggal' => date('Y-m-d'),
            'gudang_tujuan' => 'UTAMA',
            'akun_id' => $akun->id,
            'keterangan' => 'Test Feature Item Masuk',
            'details' => [
                [
                    'item_id' => $item->id,
                    'qty' => 5,
                    'satuan' => 'PCS',
                    'harga' => 12000, // Different from harga_pokok
                ]
            ]
        ];

        // 5. Act
        $response = $this->actingAs($user)->post(route('persediaan.storeMasuk'), $data);

        // 6. Assert
        $response->assertRedirect(route('persediaan.masuk'));
        $response->assertSessionHas('success');

        // Verify Database
        $this->assertDatabaseHas('item_masuks', [
            'no_transaksi' => $no_transaksi,
            'total_nilai' => 60000, // 5 * 12000
        ]);

        $this->assertDatabaseHas('item_masuk_details', [
            'item_id' => $item->id,
            'qty' => 5,
            'harga' => 12000,
        ]);

        // Verify Stock Updated
        $updatedItem = Item::find($item->id);
        $this->assertEquals(15, $updatedItem->stok); // 10 + 5

        // Verify Harga Pokok NOT Updated (LIFO requirement)
        $this->assertEquals(10000, $updatedItem->harga_pokok);

        // Cleanup
        \App\Models\KartuStok::where('no_referensi', $no_transaksi)->delete();
        \App\Models\ItemMasukDetail::whereHas('item_masuk', function($q) use ($no_transaksi) {
            $q->where('no_transaksi', $no_transaksi);
        })->delete();
        ItemMasuk::where('no_transaksi', $no_transaksi)->delete();
        $item->delete();
    }
}
