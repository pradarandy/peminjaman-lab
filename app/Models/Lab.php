<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $table = 'lab';
    protected $primaryKey = 'id_lab';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'pic',
        'status',
    ];
}
