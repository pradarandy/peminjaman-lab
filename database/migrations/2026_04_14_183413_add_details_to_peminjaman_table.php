<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->text('daftar_nama')->nullable()->after('keterangan');
            $table->string('ketua_kegiatan')->nullable()->after('daftar_nama');
            $table->string('kontak_ketua')->nullable()->after('ketua_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['daftar_nama', 'ketua_kegiatan', 'kontak_ketua']);
        });
    }
};
