<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. Menampilkan daftar seluruh akun
    public function index(Request $request)
    {
        // Fitur ini dibatasi hanya untuk user selain 'mahasiswa'
        if (Auth::user()->role === 'mahasiswa') {
            return redirect('/dashboard')->withErrors('Akses Ditolak: Anda tidak memiliki wewenang membuka halaman Manajemen Akun.');
        }

        $users = User::all();
        return view('users.index', compact('users'));
    }

    // 2. Memproses penambahan akun dari modal tambah akun
    public function store(Request $request)
    {
        if (Auth::user()->role === 'mahasiswa') {
            return redirect('/dashboard')->withErrors('Akses Ditolak: Anda tidak memiliki wewenang.');
        }

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:mahasiswa,laboran,kajur,wadir',
            'rfid_uid' => 'nullable|string|unique:user,rfid_uid',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
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
}
