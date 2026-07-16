<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalKuliah;
use App\Models\Lab;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'mahasiswa') {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $jadwal = JadwalKuliah::with('lab')->orderBy('hari', 'asc')->orderBy('jam_mulai', 'asc')->get();
        $labs = Lab::all();

        return view('jadwal.index', compact('jadwal', 'labs'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'mahasiswa') {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $request->validate([
            'id_lab' => 'required|integer',
            'mata_kuliah' => 'required|string|max:255',
            'dosen' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        JadwalKuliah::create($request->all());

        return back()->with('success', 'Jadwal Mata Kuliah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role === 'mahasiswa') {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $request->validate([
            'id_lab' => 'required|integer',
            'mata_kuliah' => 'required|string|max:255',
            'dosen' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal = JadwalKuliah::findOrFail($id);
        $jadwal->update($request->all());

        return back()->with('success', 'Jadwal Mata Kuliah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'mahasiswa') {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $jadwal = JadwalKuliah::findOrFail($id);
        $jadwal->delete();

        return back()->with('success', 'Jadwal berhasil dihapus!');
    }
}
