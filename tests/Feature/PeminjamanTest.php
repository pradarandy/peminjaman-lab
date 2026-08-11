<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lab;

class PeminjamanTest extends TestCase
{

    public function test_mahasiswa_can_view_peminjaman_form()
    {
        $user = User::create([
            'username' => 'mahasiswa1',
            'email' => 'mhs1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa'
        ]);

        $response = $this->actingAs($user)->get('/peminjaman/create');
        $response->assertStatus(200);
    }

    public function test_mahasiswa_can_submit_peminjaman_form()
    {
        $user = User::create([
            'username' => 'mahasiswa1',
            'email' => 'mhs1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
            'nim' => '2257301111'
        ]);

        $lab = Lab::create(['nama' => 'Lab Komputer']);

        $response = $this->actingAs($user)->post('/peminjaman/store-web', [
            'id_lab' => [$lab->id_lab],
            'kebutuhan_alat' => 'PC',
            'tgl_mulai' => now()->addDays(1)->format('Y-m-d'),
            'tgl_selesai' => now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'keterangan' => 'Praktikum',
            'daftar_nama' => [(string)$user->id_user],
            'pembimbing' => 'Bapak Dosen',
            'email_pembimbing' => 'dosen@example.com',
            'ketua_kegiatan' => 'Mahasiswa 1',
            'kontak_ketua' => '081234567890',
            'nim' => '2257301111'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('peminjaman', [
            'id_user' => $user->id_user,
            'keterangan' => 'Praktikum'
        ]);
    }

    public function test_peminjaman_fails_if_required_fields_missing()
    {
        $user = User::create([
            'username' => 'mahasiswa1',
            'email' => 'mhs1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa'
        ]);

        // Submit form tanpa id_lab, tgl, dll
        $response = $this->actingAs($user)->post('/peminjaman/store-web', [
            'keterangan' => 'Praktikum',
        ]);

        $response->assertSessionHasErrors(['id_lab', 'tgl_mulai', 'jam_mulai']);
    }
}
