<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTransfer extends Model
{
    use HasFactory;

    protected $table = 'item_transfers';

    protected $fillable = [
        'no_transaksi',
        'tanggal',
        'gudang_asal',
        'gudang_tujuan',
        'no_sj',
        'pic',
        'keterangan',
        'user_id',
    ];

    public function details()
    {
        return $this->hasMany(ItemTransferDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
