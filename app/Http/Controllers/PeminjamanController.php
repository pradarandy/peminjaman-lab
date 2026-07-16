<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifikasiPeminjamanMail;
use App\Models\User;
use App\Models\Lab;
use App\Models\JadwalKuliah;

class PeminjamanController extends Controller
{
    //Fungsi untuk menyimpan pengajuan peminjaman dari mahasiswa
    public function store (Request $request)
    {
        //1. Validasi input dari form
        $request->validate([
            'id_lab' => 'required|array',
            'id_lab.*' => 'integer',
            'id_asset' => 'nullable|array',
            'tgl_mulai' => 'required|date|after_or_equal:today',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'required|string',
            'daftar_nama' => 'required|array',
            'pembimbing' => 'required|string',
            'ketua_kegiatan' => 'required|string',
            'kontak_ketua' => 'required|string',
        ]);

        // 1.5 Cek Bentrok Jadwal Mata Kuliah Rutin
        $map_hari = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $hari_indo = $map_hari[Carbon::parse($request->tgl_mulai)->format('l')];

        foreach ($request->id_lab as $lab_id) {
            $bentrokKuliah = JadwalKuliah::where('id_lab', $lab_id)
                ->where('hari', $hari_indo)
                ->where(function ($query) use ($request) {
                    $query->where('jam_mulai', '<', $request->jam_selesai)
                          ->where('jam_selesai', '>', $request->jam_mulai);
                })->first();

            if ($bentrokKuliah) {
                return response()->json([
                    'message' => 'Peminjaman gagal: Jadwal berbenturan dengan mata kuliah rutin ('.$bentrokKuliah->mata_kuliah.').'
                ], 422);
            }

            // 1.6 Cek Bentrok Peminjaman Insidental Lainnya
            $bentrokPeminjaman = Peminjaman::whereJsonContains('id_lab', (string)$lab_id)
                ->whereDate('tgl_mulai', $request->tgl_mulai)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($request) {
                    $query->where('jam_mulai', '<', $request->jam_selesai)
                          ->where('jam_selesai', '>', $request->jam_mulai);
                })->exists();

            if ($bentrokPeminjaman) {
                return response()->json([
                    'message' => 'Peminjaman gagal: Ruangan sudah dibooking/digunakan pada jam tersebut.'
                ], 422);
            }
        }

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
            'id_lab' => array_map('strval', $request->id_lab), // cast ids to strings for json
            'id_asset' => $request->id_asset ? array_map('strval', $request->id_asset) : null,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
            'daftar_nama' => array_map('strval', $request->daftar_nama),
            'pembimbing' => $request->pembimbing,
            'ketua_kegiatan' => $request->ketua_kegiatan,
            'kontak_ketua' => $request->kontak_ketua,
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
        $labs = Lab::all();
        $mahasiswas = \App\Models\User::where('role', 'mahasiswa')->get();
        return view('peminjaman.create', compact('labs', 'mahasiswas'));
    }

    //2. Memproses Data dari Form Web HTML
    public function storeWeb(Request $request)
    {
        //.1 Validasi input form web
        $request->validate([
            'id_lab' => 'required|array',
            'id_lab.*' => 'integer',
            'id_asset' => 'nullable|array',
            'tgl_mulai' => 'required|date|after_or_equal:today',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'required|string',
            'daftar_nama' => 'required|array',
            'pembimbing' => 'required|string',
            'ketua_kegiatan' => 'required|string',
            'kontak_ketua' => 'required|string',
        ]);

        // 1.5 Cek Bentrok Jadwal Mata Kuliah Rutin
        $map_hari = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $hari_indo = $map_hari[Carbon::parse($request->tgl_mulai)->format('l')];

        foreach ($request->id_lab as $lab_id) {
            $bentrokKuliah = JadwalKuliah::where('id_lab', $lab_id)
                ->where('hari', $hari_indo)
                ->where(function ($query) use ($request) {
                    $query->where('jam_mulai', '<', $request->jam_selesai)
                          ->where('jam_selesai', '>', $request->jam_mulai);
                })->first();

            if ($bentrokKuliah) {
                return back()->withErrors('Peminjaman gagal: Jadwal berbenturan dengan mata kuliah rutin ('.$bentrokKuliah->mata_kuliah.') pada jam tersebut.')->withInput();
            }

            // 1.6 Cek Bentrok Peminjaman Insidental Lainnya
            $bentrokPeminjaman = Peminjaman::whereJsonContains('id_lab', (string)$lab_id)
                ->whereDate('tgl_mulai', $request->tgl_mulai)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($request) {
                    $query->where('jam_mulai', '<', $request->jam_selesai)
                          ->where('jam_selesai', '>', $request->jam_mulai);
                })->exists();

            if ($bentrokPeminjaman) {
                return back()->withErrors('Peminjaman gagal: Ruangan sudah dibooking/digunakan pada jam tersebut. Silakan cek menu Status Ruangan.')->withInput();
            }
        }

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
            'id_lab' => array_map('strval', $request->id_lab),
            'id_asset' => $request->id_asset ? array_map('strval', $request->id_asset) : null,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
            'daftar_nama' => array_map('strval', $request->daftar_nama),
            'pembimbing' => $request->pembimbing,
            'ketua_kegiatan' => $request->ketua_kegiatan,
            'kontak_ketua' => $request->kontak_ketua,
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

            // Generate Signed URLs untuk fitur One-Click Approval Email
            $approveUrl = \Illuminate\Support\Facades\URL::signedRoute('peminjaman.email_approve', ['id' => $peminjaman_baru->id]);
            $rejectUrl = \Illuminate\Support\Facades\URL::signedRoute('peminjaman.email_reject', ['id' => $peminjaman_baru->id]);

            Mail::to($approver->email)->send(new NotifikasiPeminjamanMail($peminjaman_baru, $mahasiswa, $approveUrl, $rejectUrl));
         }

        //4. Redirect kembali ke halaman dashboard dengan pesan sukses
        return redirect('/dashboard')->with('success', 'Pengajuan peminjaman berhasil dikirim dan menunggu persetujuan');

    }

    //3. Menampilkan halaman detail persetujuan
    public function showWeb($id)
    {
        // Memanfaatkan Relasi Eloquent (Eager Loading) agar lebih efisien dan bersih
        $peminjaman = Peminjaman::with(['user'])->findOrFail($id);
        
        $mahasiswa = $peminjaman->user;

        return view('peminjaman.show', compact('peminjaman', 'mahasiswa'));
    }

    // 4. Fitur Cek Status Ruangan
    public function cekStatus(Request $request)
    {
        $id_lab = $request->id_lab;
        $tanggal = $request->tanggal;

        $labs = Lab::all();
        
        // Default pencarian adalah hari ini jika belum memilih
        if (!$tanggal) {
            $tanggal = Carbon::today()->toDateString();
        }

        // Tentukan Hari dalam Bahasa Indonesia
        $map_hari = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $hari_indo = $map_hari[Carbon::parse($tanggal)->format('l')];

        // 1. Cari jadwal Peminjaman (Insidental)
        $queryPeminjaman = Peminjaman::query()
                    ->whereIn('status', ['pending', 'approved'])
                    ->whereDate('tgl_mulai', $tanggal);
        if ($id_lab) {
            $queryPeminjaman->whereJsonContains('id_lab', strval($id_lab));
        }
        $dataPeminjaman = $queryPeminjaman->get()->map(function ($item) {
            $item->tipe_jadwal = 'peminjaman';
            return $item;
        });

        // 2. Cari Jadwal Kuliah Rutin
        $queryJadwal = JadwalKuliah::with('lab')->where('hari', $hari_indo);
        if ($id_lab) {
            $queryJadwal->where('id_lab', $id_lab);
        }
        $dataJadwal = $queryJadwal->get()->map(function ($item) {
            $item->tipe_jadwal = 'kuliah';
            return $item;
        });

        // 3. Gabungkan dan Urutkan berdasarkan jam mulai
        $jadwal = $dataPeminjaman->concat($dataJadwal)->sortBy('jam_mulai')->values();

        return view('peminjaman.cek_status', compact('labs', 'jadwal', 'id_lab', 'tanggal'));
    }

    // 5. Fitur One-Click Approval Email (Approve)
    public function emailApprove($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status != 'pending') {
            return view('peminjaman.email_action', ['status' => 'expired', 'peminjaman' => $peminjaman]);
        }

        $peminjaman->update(['status' => 'approved']);
        return view('peminjaman.email_action', ['status' => 'approved', 'peminjaman' => $peminjaman]);
    }

    // 6. Fitur One-Click Approval Email (Reject)
    public function emailReject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status != 'pending') {
            return view('peminjaman.email_action', ['status' => 'expired', 'peminjaman' => $peminjaman]);
        }

        $peminjaman->update(['status' => 'rejected']);
        return view('peminjaman.email_action', ['status' => 'rejected', 'peminjaman' => $peminjaman]);
    }
}
