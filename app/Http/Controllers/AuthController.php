<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // OLD: if (isset($_SESSION['user_id'])) redirect to index.php
    public function showLogin()
    {
        if (Auth::check()) return $this->redirectByRole();
        return view('auth.login');
    }

    // OLD: login.php POST logic
    public function login(Request $request)
    {
        $username = trim($request->input('username'));
        $password = $request->input('password');

        if (empty($username) || empty($password)) {
            return back()->with('error', 'Please enter both username and password.');
        }

        // OLD: $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?")
        $user = User::where('username', $username)->first();

        // OLD: password_verify($password, $user['password'])
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            return $this->redirectByRole();
        }

        return back()->with('error', 'Invalid username or password.');
    }

    // OLD: register.php POST logic
    public function showRegister()
    {
        if (Auth::check()) return $this->redirectByRole();
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $username = trim($request->input('username'));
        $password = $request->input('password');
        $phone    = trim($request->input('phone_number'));

        if (empty($username) || empty($password) || empty($phone)) {
            return back()->with('error', 'Please fill in all fields.');
        }

        // OLD: $pdo->prepare("SELECT id FROM users WHERE username = ?")
        if (User::where('username', $username)->exists()) {
            return back()->with('error', 'Username already exists.');
        }

        // OLD: password_hash($password, PASSWORD_DEFAULT)
        User::create([
            'username'     => $username,
            'password'     => Hash::make($password),
            'phone_number' => $phone,
            'role'         => 'employee',
        ]);

        return redirect()->route('login');
    }

    // OLD: logout.php — session_destroy()
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // OLD: index.php — role-based redirect
    private function redirectByRole()
    {
        return match(Auth::user()->role) {
            'employee'   => redirect()->route('employee.dashboard'),
            'technician' => redirect()->route('technician.dashboard'),
            'admin'      => redirect()->route('technician.dashboard'),
            default      => redirect()->route('login'),
        };
    }
}
