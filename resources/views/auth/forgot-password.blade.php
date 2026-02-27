@extends('layouts.app')

@section('title', 'ลืมรหัสผ่าน')

@section('content')
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>

    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2fd4b3);
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
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
            cursor: pointer;
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
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-title">ลืมรหัสผ่าน</div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">อีเมล</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    placeholder="กรอกอีเมลของคุณ"
                    required
                >

                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-login">
                ส่งลิงก์รีเซ็ตรหัสผ่าน
            </button>
        </form>
    </div>
</div>

{{-- SweetAlert2 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Laravel จะส่ง session('status') มาเมื่อส่งลิงก์เข้าอีเมลสำเร็จ
        @if(session('status'))
            Swal.fire({
                icon: 'success',
                title: 'ส่งอีเมลสำเร็จ!',
                text: 'เราได้ส่งลิงก์สำหรับรีเซ็ตรหัสผ่านไปที่อีเมลของคุณแล้ว',
                toast: true,
                position: 'top-end', // แจ้งเตือนมุมขวาบน
                showConfirmButton: false,
                timer: 4000, // โชว์ 4 วินาที (ให้เวลาอ่านนิดนึง)
                timerProgressBar: true
            });
        @endif
    });
</script>

</body>
</html>
@endsection