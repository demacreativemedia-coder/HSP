<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
 public function login(){if(Auth::check())return redirect()->route('admin.dashboard');return view('admin.login');}
 public function authenticate(Request $r){$d=$r->validate(['email'=>'required|email','password'=>'required|string']);if(Auth::attempt($d)){ $r->session()->regenerate();\App\Support\AdminAudit::record('auth.login');if(!Auth::user()->can('dashboard.view')){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return back()->withErrors(['email'=>'Akun tidak memiliki izin dashboard.']);}return redirect()->intended(route('admin.dashboard'));}return back()->withErrors(['email'=>'Email atau kata sandi tidak cocok.'])->onlyInput('email');}
 public function logout(Request $r){\App\Support\AdminAudit::record('auth.logout');Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('login');}
}
