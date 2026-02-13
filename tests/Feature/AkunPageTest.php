<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AkuntansiAkun;

class AkunPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_akun_page()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_test'
        ]);

        $response = $this->actingAs($user)->get(route('akuntansi.akun.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Perkiraan');
        $response->assertSee('Tambah Akun Baru');
        
        // Check for the modal elements I added/modified
        $response->assertSee('modal-dialog-centered');
        $response->assertSee('Asset (Harta)');
        $response->assertSee('Contoh: Kas Besar'); // Placeholder check
    }

    public function test_can_create_new_akun()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_test'
        ]);

        $response = $this->actingAs($user)->post(route('akuntansi.akun.store'), [
            'kode_akun' => '1101',
            'nama_akun' => 'Kas Kecil',
            'tipe' => 'asset',
            'saldo_normal' => 'debit'
        ]);

        $response->assertRedirect(route('akuntansi.akun.index'));
        $this->assertDatabaseHas('akuntansi_akun', [
            'kode_akun' => '1101',
            'nama_akun' => 'Kas Kecil'
        ]);
    }
}
