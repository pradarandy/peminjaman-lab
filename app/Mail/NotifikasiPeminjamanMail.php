<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiPeminjamanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;
    public $mahasiswa;

    //kita menangkap data peminjaman dan data mahasiswa yang mengajukan
    public function __construct($peminjaman, $mahasiswa)
    {
        $this->peminjaman = $peminjaman;
        $this->mahasiswa = $mahasiswa;
    }

    public function build()
    {
        return $this->subject('Menunggu Persetujuan: Peminjam Lab')
                    ->view('emails.notifikasi_peminjaman');
    }
}