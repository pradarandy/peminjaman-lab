<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lab;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MahasiswaBlackBoxTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createMahasiswa()
    {
        return User::create([
            'username' => 'Test Mahasiswa',
            'nim' => '123456789',
            'email' => 'mahasiswa@example.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);
    }

    private function createLab()
    {
        return Lab::create([
            'nama' => 'Lab Test Black Box',
            'pic' => 'PIC Test',
            'status' => 'tersedia'
        ]);
    }

    /**
     * Skenario 1: Pengajuan Peminjaman (Berhasil)
     */
    public function test_pengajuan_peminjaman_berhasil()
    {
        $mahasiswa = $this->createMahasiswa();
        $lab = $this->createLab();

        // Simulate login and submit form
        $response = $this->actingAs($mahasiswa)->post('/peminjaman/store-web', [
            'id_lab' => [$lab->id_lab],
            'kebutuhan_alat' => '1 PC, 1 Proyektor',
            'tgl_mulai' => Carbon::tomorrow()->format('Y-m-d'),
            'tgl_selesai' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'keterangan' => 'Uji Coba Penelitian',
            'daftar_nama' => ['Peserta 1', 'Peserta 2'],
            'pembimbing' => 'Dosen Pembimbing',
            'email_pembimbing' => 'dosen@example.com',
            'ketua_kegiatan' => 'Ketua Test',
            'kontak_ketua' => '08123456789',
            'nim' => '123456789'
        ]);

        // Assert redirect to dashboard and has success message
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success');

        // Assert database has record and status is pending_pembimbing
        $this->assertDatabaseHas('peminjaman', [
            'id_user' => $mahasiswa->id_user,
            'kebutuhan_alat' => '1 PC, 1 Proyektor',
            'status' => 'pending_pembimbing'
        ]);
    }

    /**
     * Skenario 2: Validasi Jadwal Bentrok
     */
    public function test_validasi_jadwal_bentrok()
    {
        $mahasiswa = $this->createMahasiswa();
        $lab = $this->createLab();

        // Create an existing booking for tomorrow 09:00 - 11:00
        $tgl = Carbon::tomorrow()->format('Y-m-d');
        Peminjaman::create([
            'id_user' => $mahasiswa->id_user,
            'id_lab' => json_encode([strval($lab->id_lab)]),
            'tgl_mulai' => $tgl,
            'tgl_selesai' => $tgl,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'keterangan' => 'Booking Awal',
            'kebutuhan_alat' => '-',
            'daftar_nama' => json_encode([]),
            'pembimbing' => 'Dosen Pembimbing',
            'email_pembimbing' => 'dosen@example.com',
            'ketua_kegiatan' => 'Ketua Test',
            'kontak_ketua' => '08123456789',
            'status' => 'approved', // Status yang dibooking
            'level' => '1'
        ]);

        // Submit new booking overlapping the time (08:00 - 10:00)
        $response = $this->actingAs($mahasiswa)->post('/peminjaman/store-web', [
            'id_lab' => [$lab->id_lab],
            'kebutuhan_alat' => '-',
            'tgl_mulai' => $tgl,
            'tgl_selesai' => $tgl,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'keterangan' => 'Booking Bentrok',
            'daftar_nama' => [],
            'pembimbing' => 'Dosen Pembimbing',
            'email_pembimbing' => 'dosen@example.com',
            'ketua_kegiatan' => 'Ketua Test',
            'kontak_ketua' => '08123456789',
            'nim' => '123456789'
        ]);

        // Assert that session has validation error for overlap
        $response->assertSessionHasErrors();
        
        // Assert database does not have the new booking
        $this->assertDatabaseMissing('peminjaman', [
            'keterangan' => 'Booking Bentrok'
        ]);
    }

    /**
     * Skenario 3: Detail Riwayat Peminjaman
     */
    public function test_detail_riwayat_peminjaman()
    {
        $mahasiswa = $this->createMahasiswa();
        $lab = $this->createLab();

        // Create a booking
        $peminjaman = Peminjaman::create([
            'id_user' => $mahasiswa->id_user,
            'id_lab' => json_encode([strval($lab->id_lab)]),
            'tgl_mulai' => Carbon::tomorrow()->format('Y-m-d'),
            'tgl_selesai' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_mulai' => '13:00',
            'jam_selesai' => '15:00',
            'keterangan' => 'Testing View Detail',
            'kebutuhan_alat' => 'Router',
            'daftar_nama' => json_encode([]),
            'pembimbing' => 'Dosen Pembimbing',
            'email_pembimbing' => 'dosen@example.com',
            'ketua_kegiatan' => 'Ketua Test',
            'kontak_ketua' => '08123456789',
            'status' => 'pending_laboran',
            'level' => '1'
        ]);

        // View detail page
        $response = $this->actingAs($mahasiswa)->get('/peminjaman/detail/' . $peminjaman->id);

        // Assert response OK and see details
        $response->assertStatus(200);
        $response->assertSee('Dokumen Peminjaman');
        $response->assertSee('Testing View Detail');
        $response->assertSee('Router');
        $response->assertSee('Jejak Persetujuan'); // Memastikan visualisasi stepper ada
    }

    /**
     * Skenario 4: Cetak Form Peminjaman
     */
    public function test_cetak_form_peminjaman()
    {
        $mahasiswa = $this->createMahasiswa();
        $lab = $this->createLab();

        // Create an approved booking
        $peminjaman = Peminjaman::create([
            'id_user' => $mahasiswa->id_user,
            'id_lab' => json_encode([strval($lab->id_lab)]),
            'tgl_mulai' => Carbon::tomorrow()->format('Y-m-d'),
            'tgl_selesai' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_mulai' => '07:00',
            'jam_selesai' => '09:00',
            'keterangan' => 'Testing Cetak Form',
            'kebutuhan_alat' => '-',
            'daftar_nama' => json_encode([]),
            'pembimbing' => 'Dosen Pembimbing',
            'email_pembimbing' => 'dosen@example.com',
            'ketua_kegiatan' => 'Ketua Test',
            'kontak_ketua' => '08123456789',
            'status' => 'approved',
            'level' => '1'
        ]);

        // Access print page
        $response = $this->actingAs($mahasiswa)->get('/peminjaman/print/' . $peminjaman->id);

        // Assert response OK and see print layout components
        $response->assertStatus(200);
        $response->assertSee('BUKTI PERSETUJUAN PEMINJAMAN LABORATORIUM');
        $response->assertSee('window.print()');
        $response->assertSee('DISETUJUI SEPENUHNYA');
    }
}
