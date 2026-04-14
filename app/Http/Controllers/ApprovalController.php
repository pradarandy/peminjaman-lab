<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Models\Approval;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function store(Request $request, $id)
    {
        //1. validasi input
        $request->validate([
            'id_approver' => 'required|integer',
            'status' => 'required|in:approved,rejected',
        ]);

        //2. Cari data peminjaman berdasarkan ID
        $peminjaman = Peminjaman::find($id);

        if(!$peminjaman) {
            return response()->json(['message' => 'Data peminjaman tidak ditemukan'], 404);
        }

        //3. Simpan riwayat ke tabel approval
        $approval = Approval::create([
            'id_peminjaman' => $peminjaman->id,
            'id_approver' => $request->id_approver,
            'level' => $peminjaman->level, //ambil level dari data peminjaman
            'status' => $request->status,
            'tgl_acc' => Carbon::now(), //Catat waktu saat ini
        ]);

        //4. Update status di tabel peminjaman utama
        $peminjaman->status = $request->status;
        $peminjaman->save();

        //5. Kembalikan respons sukses
        return response()->json([
            'message' => 'Status peminjaman berhasil diperbarui menjadi ' . $request->status,
            'data_peminjaman' => $peminjaman,
            'riwayat_approval' => $approval
            
        ], 200);
    }

    public function storeWeb(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        //cari data peminjaman berdasarkan ID
        $peminjaman = Peminjaman::where('id', $id)->first();

        if (!$peminjaman) {
            return back()->withErrors('Data peminjaman tidak ditemukan.');
        }

        //Update status
        $peminjaman->update([
            'status' => $request->status
        ]);

        $kata_status = $request->status == 'approved' ? 'disetujui' : 'ditolak';

        return back()->with('success', "Pengajuan peminjaman berhasil $kata_status!");
    }
}
