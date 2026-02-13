<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'item_keluar_details';

    protected $fillable = [
        'item_keluar_id',
        'item_id',
        'qty',
        'satuan',
        'keterangan',
    ];

    public function item_keluar()
    {
        return $this->belongsTo(ItemKeluar::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
