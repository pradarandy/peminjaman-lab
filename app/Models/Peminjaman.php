<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table ='peminjaman';
    // protected $primaryKey = 'id';
    // // Default Laravel sudah 'id', jadi baris ini opsional
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_lab',
        'id_asset',
        'kebutuhan_alat',
        'tgl_mulai',
        'tgl_selesai',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
        'daftar_nama',
        'pembimbing',
        'email_pembimbing',
        'ketua_kegiatan',
        'kontak_ketua',
        'level', // 1, 2, 3
        'status', // 'pending', 'approved', 'rejected'
    ];

    protected $casts = [
        'id_lab' => 'array',
        'id_asset' => 'array',
        'daftar_nama' => 'array',
    ];

    /**
     * Relasi ke model User (Mahasiswa peminjam)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Mengambil collection Lab berdasarkan array id_lab
     */
    public function getLabsAttribute()
    {
        if (empty($this->id_lab)) return collect();
        $labs = is_array($this->id_lab) ? $this->id_lab : [$this->id_lab];
        return Lab::whereIn('id_lab', $labs)->get();
    }

    /**
     * Mengambil collection Asset berdasarkan array id_asset
     */
    public function getAssetsAttribute()
    {
        if (empty($this->id_asset)) return collect();
        $assets = is_array($this->id_asset) ? $this->id_asset : [$this->id_asset];
        return Asset::whereIn('id', $assets)->get();
    }

    /**
     * Mengambil collection User berdasarkan array daftar_nama
     */
    public function getPesertaAttribute()
    {
        if (empty($this->daftar_nama)) {
            $raw = $this->getRawOriginal('daftar_nama');
            if ($raw && !is_array(json_decode($raw, true))) {
                // If it's old text data, return a fake collection with username = raw text
                return collect([(object)['username' => $raw]]);
            }
            return collect();
        }
        $peserta = is_array($this->daftar_nama) ? $this->daftar_nama : [$this->daftar_nama];
        return User::whereIn('id_user', $peserta)->get();
    }
}
