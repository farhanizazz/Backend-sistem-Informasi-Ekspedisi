<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyewaModel extends Model
{
    use HasFactory;

    protected $table = 'master_penyewa';
    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'penanggung_jawab',
        'contact_person',
        'keterangan',
    ];

}
