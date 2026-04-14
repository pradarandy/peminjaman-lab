<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    use HasFactory;

    protected $table ='rfid';
    protected $primaryKey = 'id_rfid';
    public $timestamps = false;

    protected $fillable = [
        'uid_tag',
        'id_user',
        'status',
        'jadwal_lab',
    ];
}
