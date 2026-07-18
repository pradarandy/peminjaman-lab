<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$admin = User::where('role', 'admin')->orWhere('email', 'baa@pcr.ac.id')->first();

if (!$admin) {
    User::create([
        'username' => 'Admin BAA',
        'email' => 'baa@pcr.ac.id',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]);
    echo "Akun Admin BAA berhasil dibuat!\n";
} else {
    $admin->email = 'baa@pcr.ac.id';
    $admin->password = Hash::make('password123');
    $admin->role = 'admin';
    $admin->save();
    echo "Akun Admin BAA berhasil diperbarui!\n";
}
