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
        // Drop foreign key first
        Schema::table('peminjaman', function (Blueprint $table) {
            // $table->dropForeign('peminjaman_ibfk_2');
            // $table->dropIndex('id_lab');
        });

        // Change column types and add new columns
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->text('id_lab')->change();
            $table->text('id_asset')->nullable()->after('id_lab');
            $table->string('pembimbing')->after('kontak_ketua');
            $table->text('daftar_nama')->nullable()->change();
        });

        // Convert existing id_lab values to JSON array string
        \Illuminate\Support\Facades\DB::statement('UPDATE peminjaman SET id_lab = CONCAT("[", id_lab, "]") WHERE id_lab NOT LIKE "[%"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['id_asset', 'pembimbing']);
            $table->integer('id_lab')->change();
            
            // Add back foreign key
            $table->foreign('id_lab', 'peminjaman_ibfk_2')
                  ->references('id_lab')->on('lab')
                  ->onDelete('cascade');
        });
    }
};
