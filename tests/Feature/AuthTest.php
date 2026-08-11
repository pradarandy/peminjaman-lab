<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{

    public function test_user_can_view_login_form()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa'
        ]);

        $response = $this->post('/login-web', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa'
        ]);

        $response = $this->post('/login-web', [
            'login' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
    }
}
