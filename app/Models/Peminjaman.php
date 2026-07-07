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
        'daftar_nama',
        'ketua_kegiatan',
        'kontak_ketua',
        'level', // 1, 2, 3
        'status', // 'pending', 'approved', 'rejected'
    ];

    /**
     * Relasi ke model User (Mahasiswa peminjam)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke model Lab (Laboratorium yang dipinjam)
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'id_lab', 'id_lab');
    }
}
