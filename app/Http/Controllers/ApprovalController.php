<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Models\Approval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

        if (in_array($peminjaman->status, ['approved', 'rejected'])) {
            return back()->withErrors('Peminjaman ini sudah selesai diproses sebelumnya.');
        }

        // Validasi Otorisasi
        $userRole = Auth::user()->role;
        $authorized = false;

        if ($peminjaman->status == 'pending_laboran' && $userRole == 'laboran') {
            $authorized = true;
        } elseif ($peminjaman->status == 'pending_kajur' && $userRole == 'kajur') {
            $authorized = true;
        } elseif ($peminjaman->status == 'pending_wadir' && $userRole == 'wadir') {
            $authorized = true;
        }

        if (!$authorized) {
            return back()->withErrors('Akses Ditolak: Anda tidak memiliki wewenang atau ini bukan giliran Anda untuk menyetujui peminjaman ini.');
        }

        // Jika Ditolak, langsung reject
        if ($request->status == 'rejected') {
            $peminjaman->status = 'rejected';
            $peminjaman->save();
        } else {
            // Logika Berjenjang (Approved)
            $level = $peminjaman->level;
            $statusSekarang = $peminjaman->status;
            $role_tujuan_selanjutnya = '';

            if ($statusSekarang == 'pending_laboran') {
                if ($level == '1') {
                    $peminjaman->status = 'approved';
                } else {
                    $peminjaman->status = 'pending_kajur';
                    $role_tujuan_selanjutnya = 'kajur';
                }
            } elseif ($statusSekarang == 'pending_kajur') {
                if ($level == '2') {
                    $peminjaman->status = 'approved';
                } else {
                    $peminjaman->status = 'pending_wadir';
                    $role_tujuan_selanjutnya = 'wadir';
                }
            } elseif ($statusSekarang == 'pending_wadir') {
                $peminjaman->status = 'approved';
            }

            $peminjaman->save();

            // Kirim email ke approver selanjutnya jika ada
            if ($peminjaman->status != 'approved' && $role_tujuan_selanjutnya != '') {
                $approver = \App\Models\User::where('role', $role_tujuan_selanjutnya)->first();
                if ($approver && $approver->email) {
                    $mahasiswa = $peminjaman->user;
                    $approveUrl = \Illuminate\Support\Facades\URL::signedRoute('peminjaman.email_approve', ['id' => $peminjaman->id]);
                    $rejectUrl = \Illuminate\Support\Facades\URL::signedRoute('peminjaman.email_reject', ['id' => $peminjaman->id]);
                    \Illuminate\Support\Facades\Mail::to($approver->email)->send(new \App\Mail\NotifikasiPeminjamanMail($peminjaman, $mahasiswa, $approveUrl, $rejectUrl));
                }
            }
        }

        // Catat ke riwayat persetujuan
        Approval::create([
            'id_peminjaman' => $peminjaman->id,
            'id_approver' => Auth::user()->id_user,
            'level' => $peminjaman->level,
            'status' => $request->status,
            'tgl_acc' => Carbon::now(),
        ]);

        $kata_status = $request->status == 'approved' ? 'disetujui' : 'ditolak';

        return back()->with('success', "Pengajuan peminjaman berhasil $kata_status!");
    }
}
