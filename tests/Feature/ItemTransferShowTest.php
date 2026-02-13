<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemTransfer;
use App\Models\ItemTransferDetail;

class ItemTransferShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_transfer_page_displays_correctly()
    {
        // Create user and login
        $user = User::factory()->create([
            'username' => 'testuser',
            'role' => 'admin'
        ]);
        
        // Create Item
        $item = Item::create([
            'kode_item' => 'ITEM-TEST-TRF',
            'nama_item' => 'Item Transfer Test',
            'tipe_item' => 'inventory',
            'satuan' => 'PCS',
            'harga_pokok' => 10000,
            'harga_jual' => 15000,
            'stok' => 100,
            'hpp_system' => 'FIFO',
            'pilihan_harga' => 'satu_harga'
        ]);

        // Create Transfer
        $transfer = ItemTransfer::create([
            'no_transaksi' => 'TRF-TEST-001',
            'tanggal' => now(),
            'gudang_asal' => 'Gudang A',
            'gudang_tujuan' => 'Gudang B',
            'no_sj' => 'SJ-001',
            'pic' => 'Budi',
            'keterangan' => 'Test Transfer',
            'user_id' => $user->id
        ]);

        // Create Detail
        ItemTransferDetail::create([
            'item_transfer_id' => $transfer->id,
            'item_id' => $item->id,
            'qty' => 10,
            'satuan' => 'PCS',
            'keterangan' => 'Detail Test'
        ]);

        // Visit the show page
        $response = $this->actingAs($user)->get(route('persediaan.showTransfer', $transfer->id));

        $response->assertStatus(200);
        $response->assertSee('TRF-TEST-001');
        $response->assertSee('Gudang A');
        $response->assertSee('Gudang B');
        $response->assertSee('Item Transfer Test');
        $response->assertSee('10');
    }
}
