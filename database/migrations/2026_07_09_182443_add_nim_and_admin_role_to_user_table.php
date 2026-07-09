<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('nim', 20)->nullable()->after('username');
        });

        // Karena Doctrine/DBAL tidak selalu mendukung modifikasi ENUM dengan baik,
        // Kita gunakan DB statement mentah untuk amannya.
        DB::statement("ALTER TABLE user MODIFY COLUMN role ENUM('mahasiswa','laboran','kajur','wadir','admin') NOT NULL;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('nim');
        });
        
        DB::statement("ALTER TABLE user MODIFY COLUMN role ENUM('mahasiswa','laboran','kajur','wadir') NOT NULL;");
    }
};
