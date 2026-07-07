<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'inisial',
        'nama',
        'email_dosen',
        'nama_asset',
        'posisi_asset',
    ];
}
