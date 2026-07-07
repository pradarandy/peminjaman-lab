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
            //Laboran hanya mengurus level 1 
            $query->where('level', '1');
           }
           elseif ($user->role === 'kajur') {
            //kajur hanya mengurus level2
            $query->where('level', '2');
           }
           elseif ($user->role === 'wadir') {
            //wadir mengurus level 3
            $query->where('level', '3');
           }
       }

        // Query terpisah untuk Pagination (Tabel riwayat) - tetap sesuai role
        $riwayat = $query->paginate(10);

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
        $labModels = \App\Models\Lab::all()->keyBy('id_lab');
        $labCounts = [];
        foreach ($globalRiwayat as $item) {
            $labName = isset($labModels[$item->id_lab]) ? $labModels[$item->id_lab]->nama : 'Lab ' . $item->id_lab;
            if (!isset($labCounts[$labName])) {
                $labCounts[$labName] = 0;
            }
            $labCounts[$labName]++;
        }
        
        // Urutkan berdasarkan lab terbanyak
        arsort($labCounts);

        return view('dashboard', compact(
            'riwayat', 'user', 'totalBulanIni', 'menungguPersetujuan', 'statusCounts', 'labCounts'
        ));

    }
}