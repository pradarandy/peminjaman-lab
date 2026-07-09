<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthController extends Controller
{
    //1. Fitur Register (Untuk mendaftarkan user dengan password terenkripsi)
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'nim'      => 'nullable|string|max:20|unique:user,nim',
            'email'    => 'required|string|email|max:255|unique:user,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:mahasiswa, laboran,kajur,wadir,admin'
        ]);

        $user = User::create([
            'username' => $request->username,
            'nim'      => $request->nim,
            'email'    => $request->email,
            'password' => Hash::make($request->password), //enkripsi password otomatis
            'role'     => $request->role,
        ]);

        return response()->json(['message' => 'Registrasi berhasil, silahkan login', 'data' => $user], 201);
    }

    //2. Fitur Login (Menghasilkan Token)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        //Cek apakah user ada dan password(yang udah di enkripsi) cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau Password salah'], 401);
        }

        //Buat kunci token sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login sukses',
            'data' => $user,
            'access_token' => $token, //kunci untuk mengakses API Lain
            'token_type' => 'Bearer',
        ]);
    }

    //3. Fitur Logout (menghapus token)
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout sukses']);
    }

    //fitur login khusus web (session)
    public function loginWeb(Request $request)
    {
        //validasi input form
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // deteksi apakah yang diinput itu format email atau username biasa
        $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        //cek kecocokan di database menggunakan auth session
        if (Auth::attempt([
            $login_type => $request->login, 
            'password' => $request->password
        ])) {
            //jika berhasil, buat sesi baru untuk keamanan
            $request->session()->regenerate();

            //arahkan ke halaman dashboard
            return redirect()->intended('/dashboard');
        }
        
        //jika gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'login' => 'Username/Email atau Password salah.',
        ])->onlyInput('login');
    }

    //fitur logout khusus web
    public function logoutWeb(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Mengamankan URL redirect menggunakan fungsi route bawaan Laravel
        return redirect()->route('dashboard')->with('success', 'Anda telah berhasil sign-out.');
    }

    //Fitur Register Web

    //1. Menampilkan halaman form pendaftaran
    public function registerWebForm()
    {
        return view('auth.register');
    }

    //2. Memproses data dari form pendaftaran
    public function registerWeb(Request $request)
    {
        //validasi input
        $request->validate([
            'username' => 'required|string|max:255',
            'nim'      => 'nullable|string|max:20|unique:user,nim',
            'email'    => 'required|string|email|max:255|unique:user,email',
            'password' => 'required|string|min:6', 
        ]);

        //simpan ke database
        $user = \App\Models\User::create([
            'username' => $request->username,
            'nim'      => $request->nim,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'mahasiswa',
        ]);

        //kembali login
        return redirect('/login')->with('success', 'Registrasi berhasi!');
    }

    // --- FITUR LOGIN GOOGLE SSO ---

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();

            // 1. Validasi Domain Email
            $isPcrEmail = Str::endsWith($email, ['@pcr.ac.id', '@mahasiswa.pcr.ac.id']);
            
            if (!$isPcrEmail) {
                return redirect('/login')->withErrors([
                    'email' => 'Akses ditolak. Gunakan akun email PCR (@pcr.ac.id atau @mahasiswa.pcr.ac.id) untuk masuk ke sistem.',
                ]);
            }

            // 2. Cari atau Buat User
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Tentukan Role (Jika dosen biasanya @pcr.ac.id, mahasiswa @mahasiswa.pcr.ac.id)
                // Kita defaultkan ke mahasiswa, tapi bisa diatur admin nanti
                $role = Str::endsWith($email, '@mahasiswa.pcr.ac.id') ? 'mahasiswa' : 'laboran';

                $user = User::create([
                    'username' => $googleUser->getName(),
                    'email' => $email,
                    'password' => bcrypt(Str::random(16)), // Password acak karena pakai SSO
                    'role' => $role,
                ]);
            }

            // 3. Login User ke Web
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            return redirect('/login')->withErrors([
                'email' => 'Terjadi kesalahan saat mencoba login dengan Google. Silakan coba lagi.',
            ]);
        }
    }
}
