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

       //Query untuk mengurutkan dari yang terbaru (berdasarkan id)
       $query = Peminjaman::orderBy('id', 'desc');

       if ($user) {
           if($user->role === 'mahasiswa') {
            //mahasiswa cuma bisa lihat punya sendiri
            $query->where('id_user', $user->id_user);
           }
           elseif ($user->role === 'laboran') {
            //Laboran bisa melihat semua peminjaman (karena semua peminjaman melewati Laboran minimal)
            $query->where(function($q) use ($user) {
                $q->whereIn('level', ['1', '2', '3'])->orWhere('id_user', $user->id_user);
            });
           }
           elseif ($user->role === 'kajur') {
            //Kajur melihat peminjaman level 2 dan 3
            $query->where(function($q) use ($user) {
                $q->whereIn('level', ['2', '3'])->orWhere('id_user', $user->id_user);
            });
           }
           elseif ($user->role === 'wadir') {
            //Wadir hanya melihat peminjaman level 3
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
        $menungguPersetujuan = $globalRiwayat->filter(function($item) {
            return str_contains($item->status, 'pending');
        })->count();

        // 3. Pie Chart: Rasio Status (Global, Bulan Ini)
        $statusCounts = [
            'pending' => $menungguPersetujuan,
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