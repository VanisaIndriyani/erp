<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutang extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_piutang';

    protected $fillable = [
        'no_bayar',
        'tanggal',
        'customer_id',
        'cara_bayar',
        'no_ref',
        'akun_id',
        'total_bayar',
        'keterangan',
        'user_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
        return $this->hasMany(PembayaranPiutangDetail::class);
    }
}
