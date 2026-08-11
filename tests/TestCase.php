<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (env('DB_CONNECTION') === 'sqlite' && env('DB_DATABASE') === ':memory:') {
            $this->createTestingSchema();
        }
    }

    protected function createTestingSchema()
    {
        Schema::dropAllTables();
        
        if (!Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->id('id_user');
                $table->string('username');
                $table->string('email')->nullable();
                $table->string('password');
                $table->string('role');
                $table->string('nim')->nullable();
                $table->string('rfid_uid')->nullable();
            });
        }

        if (!Schema::hasTable('lab')) {
            Schema::create('lab', function (Blueprint $table) {
                $table->id('id_lab');
                $table->string('nama');
                $table->string('pic')->nullable();
            });
        }

        if (!Schema::hasTable('peminjaman')) {
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->id();
                $table->integer('id_user');
                $table->text('id_lab')->nullable();
                $table->integer('id_asset')->nullable();
                $table->text('kebutuhan_alat')->nullable();
                $table->date('tgl_mulai');
                $table->date('tgl_selesai');
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->text('keterangan');
                $table->text('daftar_nama')->nullable();
                $table->string('pembimbing')->nullable();
                $table->string('email_pembimbing')->nullable();
                $table->string('ketua_kegiatan')->nullable();
                $table->string('kontak_ketua')->nullable();
                $table->string('level');
                $table->string('status');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('approval')) {
            Schema::create('approval', function (Blueprint $table) {
                $table->id();
                $table->integer('id_peminjaman');
                $table->integer('id_approver');
                $table->string('level');
                $table->string('status');
                $table->dateTime('tgl_acc');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('jadwal_kuliah')) {
            Schema::create('jadwal_kuliah', function (Blueprint $table) {
                $table->id('id_jadwal');
                $table->string('mata_kuliah');
                $table->integer('id_lab');
                $table->string('dosen')->nullable();
                $table->string('hari');
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->timestamps();
            });
        }
    }
}
