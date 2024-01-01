<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TambahanModel extends Model
{
    use HasFactory;
    protected $table = 'master_tambahan';
    protected $fillable = [
        'nama',
        'sifat',
    ];
}
