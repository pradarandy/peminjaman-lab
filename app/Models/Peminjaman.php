<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table ='peminjaman';
    // protected $primaryKey = 'id'; // Default Laravel sudah 'id', jadi baris ini opsional
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_lab',
        'tgl_mulai',
        'tgl_selesai',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
        'level', // 1, 2, 3
        'status', // 'pending', 'approved', 'rejected'
    ];
}
