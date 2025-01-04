<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    use HasFactory;

    protected $table = 'log';
    protected $fillable = [
        'user_id',
        'path',
        'method',
        'request',
        'response',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
