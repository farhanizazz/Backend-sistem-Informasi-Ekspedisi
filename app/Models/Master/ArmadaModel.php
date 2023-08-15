<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArmadaModel extends Model
{
    use HasFactory;

    protected $table = 'master_armada';
    protected $fillable = [
        'nopol',
        'merk',
        'jenis',
        'tgl_stnk',
        'tgl_uji_kir',
        'status_stnk',
        'status_uji_kir',
        'keterangan',
    ];
}
