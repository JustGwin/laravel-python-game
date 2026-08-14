<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return back()
            ->withErrors(['email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'])
            ->onlyInput('email');
    }

    public function quickPlay(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:50'],
            'room_code' => ['required', 'string', 'size:6'],
        ]);

        // ── Find active room ──────────────────────────────────────────────────
        $room = Room::where('code', strtoupper(trim($request->room_code)))->first();

        if (! $room) {
            return back()
                ->withErrors(['room_code' => 'รหัสห้องไม่ถูกต้อง กรุณาตรวจสอบกับครูผู้สอน'])
                ->withInput();
        }

        if (! $room->isUsable()) {
            return back()
                ->withErrors(['room_code' => "ห้อง \"{$room->name}\" หมดอายุแล้ว"])
                ->withInput();
        }

        // ── Create guest user mapped to room ──────────────────────────────────
        $randomString = Str::random(6);
        $dummyEmail   = "guest_{$randomString}@pythongame.local";

        $user = User::create([
            'name'      => $request->name,
            'school'    => $room->school_name,
            'room_id'   => $room->id,
            'email'     => $dummyEmail,
            'password'  => Hash::make(Str::random(12)),
            'role'      => 'player',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('game.index');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }

    private function redirectByRole($user)
    {
        return match($user->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'player' => redirect()->route('game.index'),
            default  => redirect()->route('login'),
        };
    }
}
