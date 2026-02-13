<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemKeluar extends Model
{
    use HasFactory;

    protected $table = 'item_keluars';

    protected $fillable = [
        'no_transaksi',
        'tanggal',
        'gudang_asal',
        'keterangan',
        'user_id',
    ];

    public function details()
    {
        return $this->hasMany(ItemKeluarDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
