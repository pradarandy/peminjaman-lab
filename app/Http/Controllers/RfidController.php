<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peminjaman;
use Carbon\Carbon;

class RfidController extends Controller
{
    public function checkAccess(Request $request, $uid, $id_lab)
    {
        // // 1. Validasi input dari Raspberry Pi (Butuh UID Kartu dan ID Lab)
        // $request->validate([
        //     'uid' => 'required|string',
        //     'id_lab' => 'required|integer',
        // ]);

        // $uid = $request->uid;
        // $id_lab = 3;
       
        // 2. Cari User berdasarkan UID Kartu
        $user = User::where('rfid_uid', $uid)->first();

        if (!$user) {
            return response()->json([
                'status' => 'denied',
                'message' => 'Akses ditolak: Kartu tidak terdaftar.'
            ], 403);
        }

        // 3. Hak Akses Bypass (Universal) untuk Staff
        if (in_array($user->role, ['laboran', 'kajur', 'wadir'])) {
            return response()->json([
                'status' => 'granted',
                'user' => $user->username,
                'message' => 'Akses Staff Diterima: Silahkan Masuk.'
            ], 200);
        }

        // 4. Hak Akses Mahasiswa (Cek Jadwal Peminjaman)
        $now = Carbon::now();
        $tanggal_sekarang = $now->toDateString();
        $jam_sekarang = $now->format('H:i:s');

        // Cek apakah mahasiswa ini memiliki peminjaman 'approved' di lab dan waktu saat ini
        $peminjaman = Peminjaman::where('id_user', $user->id_user)
            ->whereJsonContains('id_lab', (string)$id_lab)
            ->where('status', 'approved')
            ->where('tgl_mulai', '<=', $tanggal_sekarang)
            ->where('tgl_selesai', '>=', $tanggal_sekarang)
            ->where('jam_mulai', '<=', $jam_sekarang)
            ->where('jam_selesai', '>=', $jam_sekarang)
            ->first();

        // 5. Berikan instruksi ke Raspberry Pi
        if ($peminjaman) {
            return response()->json([
                'status' => 'granted', 
                'user' => $user->username,
                'message' => 'Akses Diterima: Sesuai Jadwal Peminjaman.'
            ], 200);
        } else {
            return response()->json([
                'status' => 'denied',
                'message' => 'Akses Ditolak: Anda tidak memiliki jadwal peminjaman aktif saat ini.'
            ], 403);
        }
    }    
}
