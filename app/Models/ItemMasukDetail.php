<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'item_masuk_details';

    protected $fillable = [
        'item_masuk_id',
        'item_id',
        'qty',
        'satuan',
        'harga',
        'total',
    ];

    public function item_masuk()
    {
        return $this->belongsTo(ItemMasuk::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
