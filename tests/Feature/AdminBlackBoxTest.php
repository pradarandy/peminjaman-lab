<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lab;
use App\Models\Peminjaman;
use Carbon\Carbon;

class AdminBlackBoxTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createAdmin()
    {
        return User::create([
            'username' => 'Test Admin',
            'nim' => 'ADMIN001',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
    }

    private function createMahasiswa()
    {
        return User::create([
            'username' => 'Test Mahasiswa Baru',
            'nim' => 'MHS001',
            'email' => 'mahasiswa_baru@example.com',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
        ]);
    }

    private function createLab()
    {
        return Lab::create([
            'nama' => 'Lab Admin Test',
            'pic' => 'Admin',
            'status' => 'tersedia'
        ]);
    }

    private function createPeminjamanApproved()
    {
        $mahasiswa = $this->createMahasiswa();
        $lab = $this->createLab();

        return Peminjaman::create([
            'id_user' => $mahasiswa->id_user,
            'id_lab' => json_encode([strval($lab->id_lab)]),
            'tgl_mulai' => Carbon::tomorrow()->format('Y-m-d'),
            'tgl_selesai' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'keterangan' => 'Pengujian Cetak',
            'kebutuhan_alat' => '-',
            'daftar_nama' => json_encode([]),
            'pembimbing' => 'Dosen Pembimbing',
            'email_pembimbing' => 'dosen@example.com',
            'ketua_kegiatan' => 'Ketua A',
            'kontak_ketua' => '08111',
            'status' => 'approved', // Harus approved agar bisa dicetak
            'level' => '3'
        ]);
    }

    /**
     * Skenario 1: Tautkan UID RFID
     */
    public function test_tautkan_uid_rfid()
    {
        $admin = $this->createAdmin();
        $mahasiswa = $this->createMahasiswa();

        $response = $this->actingAs($admin)->post('/admin/rfid/update', [
            'id_user' => $mahasiswa->id_user,
            'rfid_uid' => 'AB-CD-EF-GH-12'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user', [
            'id_user' => $mahasiswa->id_user,
            'rfid_uid' => 'AB-CD-EF-GH-12'
        ]);
    }

    /**
     * Skenario 2: Tambah Jadwal Kuliah
     */
    public function test_tambah_jadwal_kuliah()
    {
        $admin = $this->createAdmin();
        $lab = $this->createLab();

        $response = $this->actingAs($admin)->post('/jadwal', [
            'id_lab' => $lab->id_lab,
            'mata_kuliah' => 'Praktikum Admin',
            'dosen' => 'Bapak Dosen',
            'hari' => 'Jumat',
            'jam_mulai' => '13:00',
            'jam_selesai' => '15:00'
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('jadwal_kuliah', [
            'mata_kuliah' => 'Praktikum Admin',
            'hari' => 'Jumat',
            'id_lab' => $lab->id_lab
        ]);
    }

    /**
     * Skenario 3: Kelola Hak Akses User
     */
    public function test_kelola_hak_akses_user()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/users', [
            'username' => 'Akun Dosen Kajur',
            'nim' => 'DOSEN001',
            'email' => 'kajur_baru@example.com',
            'password' => 'password123',
            'role' => 'kajur' // Mengubah role / membuat akun dengan role spesifik
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Hak akses berubah/tersimpan sesuai role baru
        $this->assertDatabaseHas('user', [
            'email' => 'kajur_baru@example.com',
            'role' => 'kajur'
        ]);
    }

    /**
     * Skenario 4: Cetak Form Peminjaman
     */
    public function test_cetak_form_peminjaman()
    {
        $admin = $this->createAdmin();
        $peminjaman = $this->createPeminjamanApproved();

        $response = $this->actingAs($admin)->get('/peminjaman/print/' . $peminjaman->id);

        $response->assertStatus(200);
        $response->assertSee('BUKTI PERSETUJUAN PEMINJAMAN LABORATORIUM');
        $response->assertSee('window.print()'); // Memastikan pop-up cetak PDF dipicu
    }
}
