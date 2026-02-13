<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkuntansiJurnal extends Model
{
    use HasFactory;

    protected $table = 'akuntansi_jurnal';

    protected $fillable = [
        'tanggal',
        'no_ref',
        'keterangan',
        'tipe',
    ];

    public function details()
    {
        return $this->hasMany(AkuntansiJurnalDetail::class, 'jurnal_id');
    }
}
