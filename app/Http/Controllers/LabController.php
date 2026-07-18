<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use Illuminate\Support\Facades\Auth;

class LabController extends Controller
{
    // Menampilkan halaman manajemen lab
    public function index()
    {
        // Hanya laboran, kajur, wadir yang bisa mengakses (Admin BAA dan Mahasiswa ditolak)
        $role = Auth::user()->role;
        if (!in_array($role, ['laboran', 'kajur', 'wadir', 'admin'])) {
            return redirect('/dashboard')->withErrors('Akses Ditolak: Anda tidak memiliki wewenang.');
        }

        $labs = Lab::all();
        return view('labs.index', compact('labs'));
    }

    // Menambah data lab baru
    public function store(Request $request)
    {
        $role = Auth::user()->role;
        if (!in_array($role, ['laboran', 'kajur', 'wadir', 'admin'])) {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:tersedia,digunakan,maintenance',
        ]);

        Lab::create([
            'nama' => $request->nama,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Ruang Lab berhasil ditambahkan!');
    }

    // Mengupdate data lab
    public function update(Request $request, $id)
    {
        $role = Auth::user()->role;
        if (!in_array($role, ['laboran', 'kajur', 'wadir', 'admin'])) {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:tersedia,digunakan,maintenance',
        ]);

        $lab = Lab::findOrFail($id);
        $lab->update([
            'nama' => $request->nama,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Data Lab berhasil diperbarui!');
    }

    // Menghapus data lab
    public function destroy($id)
    {
        $role = Auth::user()->role;
        if (!in_array($role, ['laboran', 'kajur', 'wadir', 'admin'])) {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $lab = Lab::findOrFail($id);
        // Pertimbangkan pengecekan relasi dengan jadwal/peminjaman sebelum menghapus
        // Jika tidak ada pengecekan, bisa error foreign key.
        try {
            $lab->delete();
            return back()->with('success', 'Ruang Lab berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors('Lab gagal dihapus karena masih terkait dengan riwayat peminjaman atau jadwal kuliah.');
        }
    }
}
