<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_item',
        'nama_item',
        'jenis',
        'merk',
        'satuan',
        'harga_pokok',
        'up_persen',
        'harga_jual',
        'stok',
        'stok_minimum',
        'pajak_include',
        'tipe_item',
        'rak',
        'hpp_system',
        'status_jual',
        'barcode',
        'pilihan_harga',
        'poin_dasar',
        'komisi_sales',
        'akun_hpp_id',
        'akun_penjualan_id',
        'akun_persediaan_id',
        'akun_biaya_non_inventory_id',
        'akun_persediaan_dalam_proses_id',
    ];

    public function kartuStok()
    {
        return $this->hasMany(KartuStok::class);
    }

    protected $casts = [
        'status_jual' => 'boolean',
        'pajak_include' => 'boolean',
    ];

    // Relationships
    public function akunHpp()
    {
        return $this->belongsTo(AkuntansiAkun::class, 'akun_hpp_id');
    }

    public function akunPenjualan()
    {
        return $this->belongsTo(AkuntansiAkun::class, 'akun_penjualan_id');
    }

    public function akunPersediaan()
    {
        return $this->belongsTo(AkuntansiAkun::class, 'akun_persediaan_id');
    }

    public function akunBiayaNonInventory()
    {
        return $this->belongsTo(AkuntansiAkun::class, 'akun_biaya_non_inventory_id');
    }

    public function akunPersediaanDalamProses()
    {
        return $this->belongsTo(AkuntansiAkun::class, 'akun_persediaan_dalam_proses_id');
    }
}
