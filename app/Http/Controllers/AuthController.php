<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm() { return view('auth.login'); }
    public function registerForm() { return view('auth.register'); }

    public function login(Request $r)
    {
        $c = $r->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($c, $r->boolean('remember'))) {
            $r->session()->regenerate();
            $staff = in_array(Auth::user()->role, ['admin', 'staff']);
            return redirect()->intended($staff ? route('admin') : route('home'));
        }
        return back()->withErrors(['email' => 'Email or password is incorrect.'])->onlyInput('email');
    }
    public function register(Request $r)
    {
        $d = $r->validate(['name' => 'required|max:100', 'email' => 'required|email|unique:users', 'password' => 'required|min:8|confirmed']);
        Auth::login(User::create($d));
        $r->session()->regenerate();
        return redirect()->route('home');
    }
    public function logout(Request $r)
    {
        Auth::logout(); $r->session()->invalidate(); $r->session()->regenerateToken();
        return redirect()->route('home');
    }
}
