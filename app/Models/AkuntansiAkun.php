<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkuntansiAkun extends Model
{
    use HasFactory;

    protected $table = 'akuntansi_akun';

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'tipe', // asset, liability, equity, revenue, expense
        'saldo_normal', // debit, kredit
    ];

    public function jurnalDetails()
    {
        return $this->hasMany(AkuntansiJurnalDetail::class, 'akun_id');
    }
}
