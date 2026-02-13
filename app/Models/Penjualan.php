<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'no_transaksi',
        'no_po',
        'tanggal',
        'customer_id',
        'unit',
        'keterangan',
        'total',
        'status_pembayaran',
        'jumlah_terbayar',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
    }

    public function pembayaran_details()
    {
        return $this->hasMany(PembayaranPiutangDetail::class);
    }

    public function getSisaTagihanAttribute()
    {
        return $this->total - $this->jumlah_terbayar;
    }

    public function getStatusLunasAttribute()
    {
        return $this->sisa_tagihan <= 0 ? 'Lunas' : 'Belum Lunas';
    }
}
