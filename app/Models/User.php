<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // Beri tahu Laravel nama tabel yang benar
    protected $table = 'user'; 

    // Beri tahu Laravel nama Primary Key-nya
    protected $primaryKey = 'id_user'; 

    // Nonaktifkan timestamps karena tabel kita tidak punya created_at & updated_at
    public $timestamps = false; 

    // Kolom apa saja yang boleh diisi datanya (Mass Assignment)
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'rfid_uid',
    ];

    // Sembunyikan password saat data dipanggil (keamanan)
    protected $hidden = [
        'password',
    ];
}