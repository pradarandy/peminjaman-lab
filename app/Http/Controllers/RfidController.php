<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rfid;
use App\Models\Peminjaman;
use Carbon\Carbon;

class RfidController extends Controller
{
    public function checkAccess(Request $request){
    //1. Validasi  input dari ESP32 (Butuh UID Kartu dan ID Lab tempat alat dipasang)
    $request->validate([
        'uid' => 'required|string',
        'id_lab' => 'required|integer',
    ]);
        $uid = $request->uid;
        $id_lab = $request->id_lab;
       
    //2. Cek apakah kartu terdaftar dan aktif di tabel rfid
    $rfid = Rfid::where('uid_tag', $uid)->where('status', 'aktif')->first();

    if (!$rfid) {
        return response()->json([
            'status' => 'denied',
            'message' => 'Akses ditolak: Kartu tidak terdaftar atau tidak aktif.'
        ], 403);
    }

    $id_user = $rfid->id_user;

    //3. Ambil waktu server saat ini (Real-time)
    $now = Carbon::now();
    $tanggal_sekarang = $now->toDateString();
    $jam_sekarang = $now->toTimeString();

    //4. Cek apakah user memiliki jadwal 'approved' di lab tersebut pada jam ini
    $peminjaman = Peminjaman::where('id_user', $id_user)
                    ->where('id_lab', $id_lab)
                    ->where('status', 'approved')
                    ->where('tgl_mulai', '<=', $tanggal_sekarang)
                    ->where('tgl_selesai', '>=', $tanggal_sekarang)
                    ->where('jam_mulai', '<=', $jam_sekarang)
                    ->where('jam_selesai', '>=', $jam_sekarang)
                    ->first();

    //5. Berikan instruksi ke ESP32
    if ($peminjaman) {
        return response()->json([
            'status' => 'granted', //untuk membuka pintu
            'message' => 'Akses Diterima: Silahkan Masuk.'
        ], 200);
    }
    else{
        return response()->json([
            'status' => 'denied', //untuk mengunci pintu
            'message' => 'Akses Ditolak: Tidak ada jadlaw peminjaman aktif saat ini.'
        ], 403);
    }

    }    
}
