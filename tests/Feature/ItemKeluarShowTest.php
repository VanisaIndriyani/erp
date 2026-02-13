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

class ItemKeluarShowTest extends TestCase
{
    public function test_show_item_keluar_successfully()
    {
        // 1. Create User
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        // 2. Create Item with Stock
        $item = Item::create([
            'kode_item' => 'TEST-IK-SHOW-' . rand(1000, 9999),
            'nama_item' => 'Test Item Keluar Show',
            'tipe_item' => 'inventory',
            'harga_pokok' => 10000,
            'harga_jual' => 15000,
            'stok' => 20, 
            'up_persen' => 0,
            'hpp_system' => 'LIFO',
            'pilihan_harga' => 'satu_harga',
        ]);

        // 3. Create Item Keluar Data
        $no_transaksi = 'IK-SHOW-' . rand(1000, 9999);
        $itemKeluar = ItemKeluar::create([
            'no_transaksi' => $no_transaksi,
            'tanggal' => date('Y-m-d'),
            'gudang_asal' => 'UTAMA',
            'keterangan' => 'Test Show Item Keluar',
            'user_id' => $user->id,
        ]);

        ItemKeluarDetail::create([
            'item_keluar_id' => $itemKeluar->id,
            'item_id' => $item->id,
            'qty' => 5,
            'satuan' => 'PCS',
            'keterangan' => 'Rusak',
        ]);

        // 4. Act
        $response = $this->actingAs($user)->get(route('persediaan.showKeluar', $itemKeluar->id));

        // 5. Assert
        $response->assertStatus(200);
        $response->assertViewIs('modules.persediaan.keluar.show');
        $response->assertSee($no_transaksi);
        $response->assertSee('Test Item Keluar Show');

        // Cleanup
        ItemKeluarDetail::where('item_keluar_id', $itemKeluar->id)->delete();
        $itemKeluar->delete();
        $item->delete();
    }
}
