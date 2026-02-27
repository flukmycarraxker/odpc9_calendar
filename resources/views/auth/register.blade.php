@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #1e3c72, #2fd4b3);
        min-height: 100vh;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-card {
        width: 100%;
        max-width: 380px;
        background: #ffffff24;
        backdrop-filter: blur(14px);
        border-radius: 18px;
        padding: 35px 30px;
        box-shadow: 0 20px 50px rgba(0,0,0,.25);
        color: #fff;
    }

    .auth-title {
        text-align: center;
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 25px;
        letter-spacing: 1px;
    }

    .form-control {
        background: transparent;
        border: none;
        border-bottom: 1px solid rgba(255,255,255,.5);
        border-radius: 0;
        color: #fff;
        padding-left: 0;
    }

    .form-control:focus {
        background: transparent;
        box-shadow: none;
        border-color: #2dd4bf;
        color: #fff;
    }

    .form-label {
        color: rgba(255,255,255,.85);
        font-size: 13px;
        letter-spacing: 1px;
    }

    .btn-auth {
        width: 100%;
        margin-top: 25px;
        padding: 10px;
        border-radius: 30px;
        font-weight: 600;
        letter-spacing: 1px;
        background: #14b8a6;
        border: none;
        color: #000000;
        transition: .3s;
    }

    .btn-auth:hover {
        background: #2dd4bf;
    }

    .auth-footer {
        margin-top: 20px;
        text-align: center;
        font-size: 13px;
    }

    .auth-footer a {
        color: #a5f3fc;
        text-decoration: none;
    }

    .invalid-feedback {
        color: #f87171;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-title">
            Register
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- USERNAME --}}
            <div class="mb-4">
                <label class="form-label">USERNAME</label>
                <input type="text"
                       name="username"
                       class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}"
                       required>

                @error('username')
                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div class="mb-4">
                <label class="form-label">EMAIL</label>
                <input type="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       required>

                @error('email')
                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="mb-4">
                <label class="form-label">PASSWORD</label>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required>

                @error('password')
                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="mb-4">
                <label class="form-label">CONFIRM PASSWORD</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       required>
            </div>

            <button type="submit" class="btn btn-auth">
                REGISTER
            </button>

            <div class="auth-footer">
                มีบัญชีแล้ว?
                <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
            </div>

        </form>

    </div>
</div>

@endsection
