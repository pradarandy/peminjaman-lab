<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $table = 'approval';
    //primary key default 'id'
    public $timestamps = false;

    protected $fillable = [
        'id_peminjaman',
        'id_approver', // User ID siapa yang menyetujui
        'level',       // '1', '2', '3'
        'status',      // 'pending', 'approved', 'rejected'
        'tgl_acc',     // Waktu persetujuan   
        ];
}
