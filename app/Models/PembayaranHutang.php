<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranHutang extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_hutang';

    protected $fillable = [
        'no_bayar',
        'tanggal',
        'supplier_id',
        'cara_bayar',
        'no_referensi',
        'akun_id',
        'total_bayar',
        'keterangan',
        'user_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function akun()
    {
        return $this->belongsTo(AkuntansiAkun::class, 'akun_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(PembayaranHutangDetail::class);
    }
}
