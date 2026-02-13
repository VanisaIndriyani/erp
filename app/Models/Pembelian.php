<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'no_invoice',
        'no_po',
        'tanggal',
        'supplier_id',
        'gudang',
        'ppn',
        'total',
        'status_pembayaran',
        'jumlah_terbayar',
        'keterangan',
    ];

    protected $appends = ['status_lunas', 'sisa_tagihan'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function pembayaran_details()
    {
        return $this->hasMany(PembayaranHutangDetail::class);
    }

    public function getSisaTagihanAttribute()
    {
        return $this->total - $this->jumlah_terbayar;
    }

    public function getStatusLunasAttribute()
    {
        // Use the database enum value for consistency or format it
        return ucfirst($this->status_pembayaran);
    }
}
