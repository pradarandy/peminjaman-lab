<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifikasiPeminjamanMail;
use App\Models\User;

class PeminjamanController extends Controller
{
    //Fungsi untuk menyimpan pengajuan peminjaman dari mahasiswa
    public function store (Request $request)
    {
        //1. Validasi input dari form
        $request->validate([
            //'id_user' => 'required|integer',
            'id_lab' => 'required|integer',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'required|string',
        ]);

       // 2. Logika Penentuan Level Approval
        $tanggal_mulai = Carbon::parse($request->tgl_mulai);
        $jam = $request->jam_mulai; // Ambil teks jam langsung dari form (contoh: "19:00")
        
        $level = '3'; // Default ke level 3 (akhir pekan/luar jam operasional)

        // Cek apakah hari kerja (Senin - Jumat)
        if ($tanggal_mulai->isWeekday()) {
            // Cek jam 07:00 - 16:00 (Level 1 - Laboran)
            if ($jam >= '07:00' && $jam <= '16:00') {
                $level = '1';
            } 
            // Cek jam 17:00 - 22:00 (Level 2 - Kajur)
            elseif ($jam >= '17:00' && $jam <= '22:00') {
                $level = '2';
            }
        }
        //kalau akhir pekan (sabtu-minggu), otomatis level 3

        //3. Simpan data ke database
        $peminjaman = Peminjaman::create([
            'id_user' => $request->user()->id_user,
            'id_lab' => $request->id_lab,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
            'level' => $level,
            'status' => 'pending', //status awal selalu pending
        ]);

        //4. Return response 
        return response()->json([
            'message' => 'Pengajuan peminjaman berhasil dikirim.',
            'data' => $peminjaman,
            'level_required' => $level
        ], 201);
    }

    //FItur Web untuk tampilan browser

    //1.Menampilkan halaman form di browser
    public function createWeb()
    {
        return view('peminjaman.create');
    }

    //2. Memproses Data dari Form Web HTML
    public function storeWeb(Request $request)
    {
        //.1 Validasi input form web
        $request->validate([
            'id_lab'=>'required|integer',
            'tgl_mulai'=>'required|date',
            'tgl_selesai'=>'required|date|after_or_equal:tgl_mulai',
            'jam_mulai'=>'required',
            'jam_selesai'=>'required',
            'keterangan'=>'required|string',
        ]);

        //2. Logika Penentuan Level Approval
        $tanggal_mulai = Carbon::parse($request->tgl_mulai);
        $jam = $request->jam_mulai;

        $level = '3';

        if ($tanggal_mulai->isWeekday()) {
            if ($jam >= '07:00' && $jam <= '16:00') {
                $level ='1';
            } elseif ($jam >= '17:00' && $jam <= '22:00'){
                $level ='2';
            }
        }

        //3. Simpan ke database menggunakan auth sesi web
            $peminjaman_baru = Peminjaman::create([
            'id_user' => Auth::user()->id_user,
            'id_lab' => $request->id_lab,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
            'level' => $level,
            'status' => 'pending', //status awal selalu pending

        ]);

         //Logika Pengiriman Email
            $role_tujuan = '';
            if ($level == '1') {
                $role_tujuan = 'laboran';
            } elseif ($level == '2') {
                $role_tujuan = 'kajur';
            }elseif ($level == '3') {
                $role_tujuan = 'wadir';
            }

         //Cari data akun yang menjabat role tersebut di database
         $approver = User::where('role', $role_tujuan)->first();

         //jika akun/email approver ditemukan, kirim email
         if ($approver && $approver->email) {
         //mengambil data mahasiswa yang sedang login   
         $mahasiswa = Auth::user();

            Mail::to($approver->email)->send(new NotifikasiPeminjamanMail($peminjaman_baru, $mahasiswa));
         }

        //4. Redirect kembali ke halaman dashboard dengan pesan sukses
        return redirect('/dashboard')->with('success', 'Pengajuan peminjaman berhasil dikirim dan menunggu persetujuan');

    }

}
