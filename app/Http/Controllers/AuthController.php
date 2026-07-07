<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\FuncCall;

class AuthController extends Controller
{
    //1. Fitur Register (Untuk mendaftarkan user dengan password terenkripsi)
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:6',
            'role' => 'required|in:mahasiswa, laboran,kajur,wadir'
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), //enkripsi password otomatis
            'role' => $request->role,
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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //cek kecocokan di database menggunakan auth session
        if (Auth::attempt($credentials)) {
            //jika berhasil, buat sesi baru untuk keamanan
            $request->session()->regenerate();

            //arahkan ke halaman dashboard
            return redirect()->intended('/dashboard');
        }
        
        //jika gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
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
            'email' => 'required|string|email|max:255|unique:user,email',
            'password' =>'required|string|min:6', 
        ]);

        //simpan ke database
        $user = \App\Models\User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'mahasiswa',
        ]);

        //kembali login
        return redirect('/login')->with('success', 'Registrasi berhasi!');
    }
}
