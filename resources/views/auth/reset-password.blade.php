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
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 25px;
        letter-spacing: 1px;
    }

    .form-group {
        margin-bottom: 18px;
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
        color: #000000;
        padding: 10px;
        border-radius: 999px;
        font-weight: bold;
        margin-top: 18px;
        transition: .2s;
    }

    .btn-login:hover {
        background: #5eead4;
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
            ตั้งรหัสผ่านใหม่
        </div>

        @if (session('status'))
            <div style="color:#86efac; font-size:13px; text-align:center; margin-bottom:15px;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div class="form-group">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ $email ?? old('email') }}"
                    required
                    autofocus>

                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label>รหัสผ่านใหม่</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required>

                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label>ยืนยันรหัสผ่าน</label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    required>
            </div>

            <button type="submit" class="btn-login">
                รีเซ็ตรหัสผ่าน
            </button>
        </form>

    </div>
</div>

@endsection