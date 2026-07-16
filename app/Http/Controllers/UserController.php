<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. Memproses penambahan akun dari modal tambah akun
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->withErrors('Akses Ditolak: Anda tidak memiliki wewenang.');
        }

        $request->validate([
            'username' => 'required|string|max:255',
            'nim'      => 'nullable|string|max:20|unique:user,nim',
            'email'    => 'required|string|email|max:255|unique:user,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:mahasiswa,laboran,kajur,wadir,admin',
            'rfid_uid' => 'nullable|string|unique:user,rfid_uid',
        ]);

        User::create([
            'username' => $request->username,
            'nim'      => $request->nim,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'rfid_uid' => $request->rfid_uid,
        ]);

        return back()->with('success', 'Akun ' . $request->role . ' baru berhasil dibuat!');
    }

    // 3. Memproses pendaftaran RFID secara mandiri oleh user yang login
    public function updateRfid(Request $request)
    {
        $request->validate([
            'rfid_uid' => 'required|string|unique:user,rfid_uid,' . Auth::id() . ',id_user'
        ]);

        $user = User::find(Auth::id());
        $user->rfid_uid = $request->rfid_uid;
        $user->save();

        return back()->with('success', 'Kartu Pintar (RFID) Anda berhasil didaftarkan dan dihubungkan ke sistem.');
    }

    // 4. Memproses pendaftaran RFID oleh Admin BAA untuk mahasiswa tertentu
    public function updateRfidAdmin(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->withErrors('Akses Ditolak: Anda tidak memiliki wewenang.');
        }

        $request->validate([
            'id_user'  => 'required|exists:user,id_user',
            'rfid_uid' => 'required|string|unique:user,rfid_uid,' . $request->id_user . ',id_user'
        ]);

        $user = User::find($request->id_user);
        $user->rfid_uid = $request->rfid_uid;
        $user->save();

        return back()->with('success', 'RFID berhasil didaftarkan untuk pengguna ' . $user->username);
    }
}
