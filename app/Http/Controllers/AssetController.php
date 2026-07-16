<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    // Menampilkan data asset dalam format JSON untuk API
    public function apiIndex()
    {
        return response()->json(Asset::all(), 200);
    }

    // Menampilkan halaman manajemen asset
    public function index()
    {
        $role = Auth::user()->role;
        if (!in_array($role, ['laboran', 'kajur', 'wadir'])) {
            return redirect('/dashboard')->withErrors('Akses Ditolak: Anda tidak memiliki wewenang.');
        }

        $assets = Asset::all();
        $labs = \App\Models\Lab::all();
        return view('assets.index', compact('assets', 'labs'));
    }

    // Menambah data asset
    public function store(Request $request)
    {
        $role = Auth::user()->role;
        if (!in_array($role, ['laboran', 'kajur', 'wadir'])) {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $request->validate([
            'nama_asset' => 'required|string|max:255',
            'posisi_asset' => 'required|string|max:255',
        ]);

        Asset::create($request->all());

        return back()->with('success', 'Asset berhasil ditambahkan!');
    }

    // Mengupdate data asset
    public function update(Request $request, $id)
    {
        $role = Auth::user()->role;
        if (!in_array($role, ['laboran', 'kajur', 'wadir'])) {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $request->validate([
            'nama_asset' => 'required|string|max:255',
            'posisi_asset' => 'required|string|max:255',
        ]);

        $asset = Asset::findOrFail($id);
        $asset->update($request->all());

        return back()->with('success', 'Data Asset berhasil diperbarui!');
    }

    // Menghapus data asset
    public function destroy($id)
    {
        $role = Auth::user()->role;
        if (!in_array($role, ['laboran', 'kajur', 'wadir'])) {
            return redirect('/dashboard')->withErrors('Akses Ditolak.');
        }

        $asset = Asset::findOrFail($id);
        $asset->delete();

        return back()->with('success', 'Asset berhasil dihapus!');
    }
}
