<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningModel extends Model
{
    use HasFactory;
    protected $table = 'master_rekening';
    protected $fillable = [
        'nomor_rekening',
        'atas_nama',
        'nama_bank',
    ];

    

}
