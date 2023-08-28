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

    public function getKeteranganAttribute(){
        return $this->attributes['keterangan'] ?? 'Tidak ada keterangan';
    }

    public function getArmadaPajakStnkByTime($time){
        return $this->where('tgl_stnk', '<=', $time)->orderBy('tgl_stnk')->get();
    }

    public function getArmadaPajakKirByTime($time){
        return $this->where('tgl_uji_kir', '<=', $time)->orderBy('tgl_uji_kir')->get();
    }
}
