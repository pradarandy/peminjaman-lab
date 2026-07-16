<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
       $user = Auth::user(); //ambil data orang yang sedang login

       //Query untuk mengurutkan dari yang terbaru
       $query = Peminjaman::orderBy('tgl_mulai', 'desc');

       if ($user) {
           if($user->role === 'mahasiswa') {
            //mahasiswa cuma bisa lihat punya sendiri
            $query->where('id_user', $user->id_user);
           }
           elseif ($user->role === 'laboran') {
            //Laboran hanya mengurus level 1 atau melihat peminjamannya sendiri
            $query->where(function($q) use ($user) {
                $q->where('level', '1')->orWhere('id_user', $user->id_user);
            });
           }
           elseif ($user->role === 'kajur') {
            //kajur hanya mengurus level 2 atau melihat peminjamannya sendiri
            $query->where(function($q) use ($user) {
                $q->where('level', '2')->orWhere('id_user', $user->id_user);
            });
           }
           elseif ($user->role === 'wadir') {
            //wadir mengurus level 3 atau melihat peminjamannya sendiri
            $query->where(function($q) use ($user) {
                $q->where('level', '3')->orWhere('id_user', $user->id_user);
            });
           }
       }

        // Query terpisah untuk Pagination (Tabel riwayat) - tetap sesuai role
        $riwayat = $query->paginate(10);

        // Jika user adalah admin BAA, kita butuh semua data user untuk manajemen akun di dashboard
        $allUsers = [];
        if ($user && $user->role === 'admin') {
            $allUsers = \App\Models\User::all();
        }

        // Data statistik global (tidak terpengaruh role), filter bulan ini
        $currentMonth = date('m');
        $currentYear = date('Y');
        $globalRiwayat = Peminjaman::whereMonth('tgl_mulai', $currentMonth)
                                   ->whereYear('tgl_mulai', $currentYear)
                                   ->get();

        // 1. Total Pengajuan Bulan Ini (Global)
        $totalBulanIni = $globalRiwayat->count();

        // 2. Peminjaman Menunggu Persetujuan (Global, Bulan Ini)
        $menungguPersetujuan = $globalRiwayat->where('status', 'pending')->count();

        // 3. Pie Chart: Rasio Status (Global, Bulan Ini)
        $statusCounts = [
            'pending' => $globalRiwayat->where('status', 'pending')->count(),
            'approved' => $globalRiwayat->where('status', 'approved')->count(),
            'rejected' => $globalRiwayat->where('status', 'rejected')->count(),
        ];

        // 4. Bar Chart: Frekuensi Peminjaman Lab (Global, Bulan Ini)
        $labCounts = [];
        foreach ($globalRiwayat as $item) {
            foreach ($item->labs as $lab) {
                $labName = $lab->nama;
                if (!isset($labCounts[$labName])) {
                    $labCounts[$labName] = 0;
                }
                $labCounts[$labName]++;
            }
        }
        
        // Urutkan berdasarkan lab terbanyak
        arsort($labCounts);

        return view('dashboard', compact(
            'riwayat', 'user', 'totalBulanIni', 'menungguPersetujuan', 'statusCounts', 'labCounts', 'allUsers'
        ));

    }
}