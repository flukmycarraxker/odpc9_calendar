@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #1e3c72, #2fd4b3);
        min-height: 100vh;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: 100%;
        max-width: 380px;
        background: #ffffff24;
        backdrop-filter: blur(14px);
        border-radius: 18px;
        padding: 35px 30px;
        box-shadow: 0 20px 50px rgba(0,0,0,.25);
        color: #fff;
    }

    .login-title {
        text-align: center;
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 25px;
        letter-spacing: 1px;
    }

    .form-group {
        color: rgba(255,255,255,.85);
        font-size: 13px;
        letter-spacing: 1px;
    }

    .form-group label {
        font-size: 13px;
        color: #ffffff;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        width: 100%;
        background: transparent;
        border: none;
        border-bottom: 1px solid #6b7280;
        border-radius: 0;
        color: #fff;
        padding: 8px 4px;
    }

    .form-control:focus {
        outline: none;
        border-color: #2dd4bf;
        box-shadow: none;
        background: transparent;
        color: #fff;
    }

    .btn-login {
        width: 100%;
        background: #2dd4bf;
        border: none;
        color: #0f172a;
        padding: 10px;
        border-radius: 999px;
        font-weight: bold;
        margin-top: 18px;
        transition: .2s;
    }

    .btn-login:hover {
        background: #5eead4;
    }

    .remember-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #ffffff;
    }

    .forgot {
        text-align: center;
        margin-top: 14px;
    }

    .forgot a {
        color: #ffffff;
        font-size: 12px;
        text-decoration: none;
    }

    .forgot a:hover {
        color: #fff;
    }

    .invalid-feedback {
        color: #f87171;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">

        <div class="login-title">
            ตารางดูการประชุม
            สคร.9
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>USERNAME</label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    class="form-control @error('username') is-invalid @enderror"
                    required
                    autofocus
                >
                @error('username')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>PASSWORD</label>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                >
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn-login">
                LOGIN
            </button>

            @if (Route::has('password.request'))
            <div class="forgot">
                <a href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            </div>
            @endif

        </form>
    </div>
</div>

{{-- 📌 ส่วนที่เพิ่มเข้ามาใหม่: โหลด SweetAlert2 และดักจับการแจ้งเตือน --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 📌 ตรวจสอบว่าระบบเตะกลับมาพร้อมส่งข้อความ error_timeout หรือไม่
    @if(session('error_timeout'))
        Swal.fire({
            icon: 'warning', 
            title: 'เซสชันหมดอายุ!',
            text: '{!! session('error_timeout') !!}',
            confirmButtonColor: '#2dd4bf', // ใช้สีเขียวเดียวกับปุ่ม LOGIN ของคุณ
            confirmButtonText: 'เข้าสู่ระบบใหม่',
            backdrop: `rgba(0,0,0,0.6)` // ทำให้หน้าจอมืดลงให้ป๊อปอัปเด่นขึ้น
        });
    @endif
});
</script>

@endsection