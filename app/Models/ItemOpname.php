<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOpname extends Model
{
    use HasFactory;

    protected $table = 'item_opnames';

    protected $fillable = [
        'tanggal',
        'gudang',
        'item_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'keterangan',
        'user_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
