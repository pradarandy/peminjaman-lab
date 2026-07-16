<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$updates = [
    'laboran' => 'randytesting81@gmail.com',
    'kajur' => 'irenreby67@gmail.com',
    'wadir' => 'randyprada016@gmail.com',
    'admin' => 'baa@pcr.ac.id'
];

foreach ($updates as $role => $email) {
    $user = User::where('role', $role)->first();
    if ($user) {
        $user->email = $email;
        // Opsional: Samakan semua password menjadi 'password123' agar mudah login pertama kali
        $user->password = Hash::make('password123');
        $user->save();
        echo "Berhasil mengupdate email {$role} menjadi: {$email}\n";
    } else {
        echo "Akun dengan role {$role} tidak ditemukan di database live!\n";
    }
}
echo "Selesai.";
