<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    // แสดงหน้าใส่อีเมล
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // ส่งลิงก์รีเซ็ตไปยังอีเมล
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'ส่งลิงก์รีเซ็ตแล้ว')
            : back()->withErrors(['email' => 'ไม่พบอีเมลนี้ในระบบ']);
    }

    // แสดงหน้า reset password
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // บันทึกรหัสผ่านใหม่
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', 'รีเซ็ตสำเร็จ')
            : back()->withErrors(['email' => 'รีเซ็ตไม่สำเร็จ']);
    }
}
