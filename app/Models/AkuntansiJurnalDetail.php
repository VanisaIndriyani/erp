<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkuntansiJurnalDetail extends Model
{
    use HasFactory;

    protected $table = 'akuntansi_jurnal_detail';

    protected $fillable = [
        'jurnal_id',
        'akun_id',
        'debit',
        'kredit',
    ];

    public function jurnal()
    {
        return $this->belongsTo(AkuntansiJurnal::class, 'jurnal_id');
    }

    public function akun()
    {
        return $this->belongsTo(AkuntansiAkun::class, 'akun_id');
    }
}
