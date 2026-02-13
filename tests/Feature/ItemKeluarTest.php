<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemKeluar;
use App\Models\ItemKeluarDetail;
use App\Models\KartuStok;

class ItemKeluarTest extends TestCase
{
    public function test_store_item_keluar_successfully()
    {
        // 1. Create User
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        // 2. Create Item with Stock
        $item = Item::create([
            'kode_item' => 'TEST-IK-' . rand(1000, 9999),
            'nama_item' => 'Test Item Keluar',
            'tipe_item' => 'inventory',
            'harga_pokok' => 10000,
            'harga_jual' => 15000,
            'stok' => 20, // Initial Stock
            'up_persen' => 0,
            'hpp_system' => 'LIFO',
            'pilihan_harga' => 'satu_harga',
        ]);

        // 3. Prepare Data
        $no_transaksi = 'IK-TEST-' . rand(1000, 9999);
        $data = [
            'no_transaksi' => $no_transaksi,
            'tanggal' => date('Y-m-d'),
            'gudang_asal' => 'UTAMA',
            'keterangan' => 'Test Feature Item Keluar',
            'details' => [
                [
                    'item_id' => $item->id,
                    'qty' => 5,
                    'satuan' => 'PCS',
                    'keterangan' => 'Rusak',
                ]
            ]
        ];

        // 4. Act
        $response = $this->actingAs($user)->post(route('persediaan.storeKeluar'), $data);

        // 5. Assert
        $response->assertRedirect(route('persediaan.keluar'));
        $response->assertSessionHas('success');

        // Verify Database
        $this->assertDatabaseHas('item_keluars', [
            'no_transaksi' => $no_transaksi,
        ]);

        $this->assertDatabaseHas('item_keluar_details', [
            'item_id' => $item->id,
            'qty' => 5,
        ]);

        // Verify Stock Updated
        $updatedItem = Item::find($item->id);
        $this->assertEquals(15, $updatedItem->stok); // 20 - 5

        // Cleanup
        KartuStok::where('no_referensi', $no_transaksi)->delete();
        ItemKeluarDetail::whereHas('item_keluar', function($q) use ($no_transaksi) {
            $q->where('no_transaksi', $no_transaksi);
        })->delete();
        ItemKeluar::where('no_transaksi', $no_transaksi)->delete();
        $item->delete();
    }
}
