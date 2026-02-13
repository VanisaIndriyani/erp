<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemOpname;
use App\Models\KartuStok;

class ItemOpnameTest extends TestCase
{
    public function test_store_opname_successfully()
    {
        // 1. Create User
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        // 2. Create Item with Stock
        $item = Item::create([
            'kode_item' => 'TEST-OP-' . rand(1000, 9999),
            'nama_item' => 'Test Item Opname',
            'tipe_item' => 'inventory',
            'harga_pokok' => 10000,
            'harga_jual' => 15000,
            'stok' => 20, // Initial Stock
            'up_persen' => 0,
            'hpp_system' => 'LIFO',
            'pilihan_harga' => 'satu_harga',
        ]);

        // 3. Prepare Data (Physical Stock is 18, so difference is -2)
        $data = [
            'tanggal' => date('Y-m-d'),
            'gudang' => 'UTAMA',
            'item_id' => $item->id,
            'stok_fisik' => 18,
            'keterangan' => 'Test Opname Minus',
        ];

        // 4. Act
        $response = $this->actingAs($user)->post(route('persediaan.storeOpname'), $data);

        // 5. Assert
        $response->assertRedirect(route('persediaan.opname'));
        $response->assertSessionHas('success');

        // Verify Database
        $this->assertDatabaseHas('item_opnames', [
            'item_id' => $item->id,
            'stok_sistem' => 20,
            'stok_fisik' => 18,
            'selisih' => -2,
        ]);

        // Verify Stock Updated
        $updatedItem = Item::find($item->id);
        $this->assertEquals(18, $updatedItem->stok);

        // Verify Stock Card
        $this->assertDatabaseHas('kartu_stok', [
            'item_id' => $item->id,
            'jenis_transaksi' => 'opname',
            'masuk' => 0,
            'keluar' => 2, // 20 - 18 = 2 lost
        ]);

        // Cleanup
        KartuStok::where('item_id', $item->id)->delete();
        ItemOpname::where('item_id', $item->id)->delete();
        $item->delete();
    }
}
