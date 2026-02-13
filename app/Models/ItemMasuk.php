<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMasuk extends Model
{
    use HasFactory;

    protected $table = 'item_masuks';

    protected $fillable = [
        'no_transaksi',
        'tanggal',
        'gudang_tujuan',
        'keterangan',
        'akun_id',
        'user_id',
        'total_nilai',
    ];

    public function details()
    {
        return $this->hasMany(ItemMasukDetail::class);
    }

    public function akun()
    {
        return $this->belongsTo(AkuntansiAkun::class, 'akun_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
