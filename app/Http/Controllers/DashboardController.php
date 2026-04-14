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

        //eksekusi query untuk mendapatkan datanya
        $riwayat = $query->get();

        // 1. Total Pengajuan Bulan Ini
        $currentMonth = date('m');
        $currentYear = date('Y');
        $totalBulanIni = $riwayat->filter(function($item) use ($currentMonth, $currentYear) {
            return date('m', strtotime($item->tgl_mulai)) == $currentMonth && date('Y', strtotime($item->tgl_mulai)) == $currentYear;
        })->count();

        // 2. Peminjaman Menunggu Persetujuan
        $menungguPersetujuan = $riwayat->where('status', 'pending')->count();

        // 3. Pie Chart: Rasio Status
        $statusCounts = [
            'pending' => $riwayat->where('status', 'pending')->count(),
            'approved' => $riwayat->where('status', 'approved')->count(),
            'rejected' => $riwayat->where('status', 'rejected')->count(),
        ];

        // 4. Bar Chart: Frekuensi Peminjaman Lab
        $labModels = \App\Models\Lab::all()->keyBy('id_lab');
        $labCounts = [];
        foreach ($riwayat as $item) {
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