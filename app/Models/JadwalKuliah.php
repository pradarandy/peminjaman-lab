<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    protected $table = 'jadwal_kuliah';
    public $timestamps = false;

    protected $fillable = [
        'id_lab',
        'mata_kuliah',
        'dosen',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'id_lab', 'id_lab');
    }
}
