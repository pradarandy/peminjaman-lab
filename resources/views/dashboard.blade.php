<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Peminjaman Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Peminjaman Lab</a>
            <div class="d-flex">
                @auth
                    <form action="/logout-web" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit">Logout</button>
                    </form>
                @else
                    <a href="/login" class="btn btn-success btn-sm px-4">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 text-center">
                
                @auth
                    <h2 class="fw-bold mb-2">Selamat Datang, {{ auth()->user()->username }}!</h2>
                    <p class="fs-5 text-muted">
                        Anda login menggunakan akses: <span class="badge bg-success text-uppercase">{{ auth()->user()->role }}</span>
                    </p>
                @else
                    <h2 class="fw-bold mb-2">Sistem Informasi Peminjaman Lab</h2>
                    <p class="fs-5 text-muted">
                        Silakan login untuk dapat mengajukan formulir peminjaman.
                    </p>
                @endauth
                <hr class="my-4">
                
                <!-- START OF DASHBOARD ANALYTICS -->
                <div class="row g-4 mb-5 text-start">
                    <!-- Summary Cards -->
                    <div class="col-md-6">
                        <div class="card bg-primary text-white h-100 shadow-sm border-0 rounded-4" style="background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                            <div class="card-body p-4 d-flex flex-column justify-content-center">
                                <h5 class="fw-normal mb-1">Total Pengajuan Bulan Ini</h5>
                                <h2 class="display-4 fw-bold mb-0">{{ $totalBulanIni }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-warning text-dark h-100 shadow-sm border-0 rounded-4" style="background: linear-gradient(135deg, #ffc107, #ffab00);">
                            <div class="card-body p-4 d-flex flex-column justify-content-center">
                                <h5 class="fw-normal mb-1">Peminjaman Menunggu Persetujuan</h5>
                                <h2 class="display-4 fw-bold mb-0">{{ $menungguPersetujuan }}</h2>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Charts -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-4">Rasio Status Peminjaman</h5>
                                <div style="height: 250px; position: relative;">
                                    <canvas id="statusPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-4">Frekuensi Peminjaman Lab</h5>
                                <div style="height: 250px; position: relative;">
                                    <canvas id="labBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END OF DASHBOARD ANALYTICS -->

                <div class="d-flex justify-content-between align-items-center mb-3 text-start">
                    <h4 class="fw-bold m-0">Riwayat Peminjaman</h4>
                    @if(!auth()->check() || auth()->user()->role === 'mahasiswa')
                        <a href="/peminjaman/create" class="btn btn-primary">+ Ajukan Peminjaman</a>
                    @endif
                </div>

                <div class="table-responsive text-start">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th>ID Lab</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Keterangan</th>
                                <th class="text-center">Status</th>
                                
                                @if(auth()->check() && auth()->user()->role !== 'mahasiswa')
                                    <th class="text-center" style="width: 150px;">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>Lab {{ $item->id_lab }}</td>
                                    <td>{{ $item->tgl_mulai }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                    </td>
                                    <td>{{ $item->keterangan }}</td>
                                    
                                    <td class="text-center">
                                        @if($item->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>

                                    @if(auth()->check() && auth()->user()->role !== 'mahasiswa')
                                        <td class="text-center">
                                            @if($item->status == 'pending')
                                                <div class="d-flex justify-content-center gap-1">
                                                    <form action="/peminjaman/{{ $item->id }}/approval-web" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui">✔</button>
                                                    </form>
                                                    
                                                    <form action="/peminjaman/{{ $item->id }}/approval-web" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak">✖</button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted small fst-italic">Selesai</span>
                                            @endif
                                        </td>
                                    @endif

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">Belum ada riwayat peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Status Peminjaman (Doughnut Chart)
            const statusCtx = document.getElementById('statusPieChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $statusCounts['pending'] ?? 0 }},
                            {{ $statusCounts['approved'] ?? 0 }},
                            {{ $statusCounts['rejected'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#ffc107', // Warning/Yellow
                            '#198754', // Success/Green
                            '#dc3545'  // Danger/Red
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: "'Inter', 'Segoe UI', Roboto, sans-serif"
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // Chart 2: Lab Sering Dipinjam (Bar Chart)
            const labCtx = document.getElementById('labBarChart').getContext('2d');
            const labLabels = {!! json_encode(array_keys($labCounts)) !!};
            const labData = {!! json_encode(array_values($labCounts)) !!};

            new Chart(labCtx, {
                type: 'bar',
                data: {
                    labels: labLabels,
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: labData,
                        backgroundColor: 'rgba(13, 110, 253, 0.85)', // Primary Blue
                        hoverBackgroundColor: 'rgba(13, 110, 253, 1)',
                        borderRadius: 6,
                        barThickness: 'flex',
                        maxBarThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            ticks: { 
                                precision: 0,
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' kali dipinjam';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>