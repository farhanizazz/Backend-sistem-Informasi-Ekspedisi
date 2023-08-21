<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubkonModel extends Model
{
    use HasFactory;
    protected $table = "master_subkon";
    protected $fillable = [
        "Nama Perusahaan",
        "alamat",
        "penanggung_jawab",
        "ket"
    ];
}
