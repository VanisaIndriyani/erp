<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutangDetail extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_piutang_detail';

    protected $fillable = [
        'pembayaran_piutang_id',
        'penjualan_id',
        'jumlah_bayar',
        'potongan',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranPiutang::class, 'pembayaran_piutang_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}
