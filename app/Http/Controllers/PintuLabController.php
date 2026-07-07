<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PintuLabController extends Controller
{
    public function scanRfid(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'uid' => 'required|string',
        ]);

        $uid = $request->uid;

        // 2. Cari User berdasarkan UID
        $user = User::where('rfid_uid', $uid)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak: Kartu RFID tidak terdaftar.',
                'user_name' => null
            ], 403);
        }

        // 3. Hak Akses Universal (Bypass) untuk Staff
        if (in_array($user->role, ['laboran', 'kajur', 'wadir'])) {
            return response()->json([
                'status' => 'success',
                'message' => 'Akses Staff Diberikan.',
                'user_name' => $user->username
            ], 200);
        }

        // 4. Cek Jadwal Peminjaman (Untuk Mahasiswa)
        $now = Carbon::now();
        $tanggal_sekarang = $now->toDateString();
        $jam_sekarang = $now->format('H:i:s');

        // Cek apakah mahasiswa ini memiliki jadwal peminjaman 'approved' hari ini dan pada jam ini
        $peminjaman = Peminjaman::where('id_user', $user->id_user)
            ->where('status', 'approved')
            ->where('tgl_mulai', '<=', $tanggal_sekarang)
            ->where('tgl_selesai', '>=', $tanggal_sekarang)
            ->where('jam_mulai', '<=', $jam_sekarang)
            ->where('jam_selesai', '>=', $jam_sekarang)
            ->first();

        if ($peminjaman) {
            return response()->json([
                'status' => 'success',
                'message' => 'Akses Diberikan sesuai jadwal peminjaman.',
                'user_name' => $user->username
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak: Anda tidak memiliki jadwal aktif saat ini.',
                'user_name' => $user->username
            ], 403);
        }
    }
}
