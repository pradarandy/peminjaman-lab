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
            $table->string('email_pembimbing')->nullable()->after('pembimbing');
        });

        // Ubah enum menjadi string agar mendukung status berjenjang dengan mudah
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending_pembimbing'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('email_pembimbing');
        });
        
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
