<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;

    protected $table = 'm_role';
    protected $fillable = ['name', 'akses'];

    protected $casts = [
        'akses' => 'array',
    ];
}
