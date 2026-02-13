<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranHutangDetail extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_hutang_detail';

    protected $fillable = [
        'pembayaran_hutang_id',
        'pembelian_id',
        'jumlah_bayar',
        'potongan',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranHutang::class, 'pembayaran_hutang_id');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }
}
