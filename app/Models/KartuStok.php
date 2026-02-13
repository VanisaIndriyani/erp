<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStok extends Model
{
    use HasFactory;

    protected $table = 'kartu_stok';

    protected $fillable = [
        'item_id',
        'supplier_id',
        'customer_id',
        'tanggal',
        'jenis_transaksi', // masuk, keluar, opname, transfer
        'no_referensi',
        'masuk',
        'keluar',
        'saldo',
        'keterangan',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
