<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AkuntansiAkun;
use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;

class SaldoAwalTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_saldo_awal_page()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_test'
        ]);

        $response = $this->actingAs($user)->get(route('akuntansi.saldo_awal.index'));

        $response->assertStatus(200);
        $response->assertSee('Saldo Awal');
        $response->assertSee('Aktiva');
        $response->assertSee('Pasiva');
    }

    public function test_can_store_saldo_awal_batch()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_test'
        ]);

        // Create dummy accounts
        $asset = AkuntansiAkun::create([
            'kode_akun' => '1001',
            'nama_akun' => 'Kas',
            'tipe' => 'asset',
            'saldo_normal' => 'debit'
        ]);

        $equity = AkuntansiAkun::create([
            'kode_akun' => '3001',
            'nama_akun' => 'Modal',
            'tipe' => 'equity',
            'saldo_normal' => 'kredit'
        ]);

        $response = $this->actingAs($user)->post(route('akuntansi.saldo_awal.store_akun'), [
            'tanggal' => date('Y-m-d'),
            'saldo' => [
                $asset->id => 1000000,
                $equity->id => 1000000
            ]
        ]);

        $response->assertRedirect(route('akuntansi.saldo_awal.index'));
        $response->assertSessionHas('success');

        // Verify Database
        $this->assertDatabaseHas('akuntansi_jurnal', [
            'tipe' => 'saldo_awal',
            'keterangan' => 'Saldo Awal Perkiraan (Batch)'
        ]);

        $jurnal = AkuntansiJurnal::where('tipe', 'saldo_awal')->first();

        $this->assertDatabaseHas('akuntansi_jurnal_detail', [
            'jurnal_id' => $jurnal->id,
            'akun_id' => $asset->id,
            'debit' => 1000000,
            'kredit' => 0
        ]);

        $this->assertDatabaseHas('akuntansi_jurnal_detail', [
            'jurnal_id' => $jurnal->id,
            'akun_id' => $equity->id,
            'debit' => 0,
            'kredit' => 1000000
        ]);
    }
    
    public function test_saldo_awal_updates_existing_entries()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_test'
        ]);

        $asset = AkuntansiAkun::create([
            'kode_akun' => '1001',
            'nama_akun' => 'Kas',
            'tipe' => 'asset',
            'saldo_normal' => 'debit'
        ]);

        // First submission
        $this->actingAs($user)->post(route('akuntansi.saldo_awal.store_akun'), [
            'tanggal' => date('Y-m-d'),
            'saldo' => [
                $asset->id => 500000,
            ]
        ]);
        
        // Second submission (update)
        $this->actingAs($user)->post(route('akuntansi.saldo_awal.store_akun'), [
            'tanggal' => date('Y-m-d'),
            'saldo' => [
                $asset->id => 1000000,
            ]
        ]);

        // Check that we don't have duplicate journals (old one should be deleted/updated logic in controller)
        // Controller logic: deletes old 'SA-AK-%' journals first.
        
        $jurnals = AkuntansiJurnal::where('tipe', 'saldo_awal')->where('no_ref', 'like', 'SA-AK-%')->get();
        $this->assertCount(1, $jurnals);
        
        $this->assertDatabaseHas('akuntansi_jurnal_detail', [
            'akun_id' => $asset->id,
            'debit' => 1000000
        ]);
    }
}
