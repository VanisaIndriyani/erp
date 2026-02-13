<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTransferDetail extends Model
{
    use HasFactory;

    protected $table = 'item_transfer_details';

    protected $fillable = [
        'item_transfer_id',
        'item_id',
        'qty',
        'satuan',
        'keterangan',
    ];

    public function item_transfer()
    {
        return $this->belongsTo(ItemTransfer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
