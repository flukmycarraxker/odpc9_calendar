<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | ODPC9</title>
    <link rel="icon" type="image/png" href="{{ asset('https://scontent.fnak1-1.fna.fbcdn.net/v/t39.30808-6/475414096_629931546215052_936776039185741861_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=53a332&_nc_ohc=VQJeJNgIsEEQ7kNvwESmUud&_nc_oc=AdnvGamo_de4VW_QZ7FV76wsTfN2Sw4C02TYcnCCjUUEyCU2gK8gZt2X8bzFRGoRoXA&_nc_zt=23&_nc_ht=scontent.fnak1-1.fna&_nc_gid=8EFtpXZkkoVWW8_p5zuVEA&oh=00_AfvQwM8EceH-hB6FeJwvRInsKeMyFlnsugnbz-qTYxLRGQ&oe=69A5B835') }}">

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @include('sweetalert2::index')
    {{-- 📌 โหลด SweetAlert2 CDN 📌 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<div id="app">

    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                ปฏิทินการประชุม 🗓️
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">🔐 เข้าสู่ระบบ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">📝 สมัครสมาชิก</a>
                        </li>
                    @endguest

                    @auth
                        {{-- 📌 เช็คสิทธิ์ Admin: ถ้า role_id == 1 ให้แสดงปุ่มจัดการสมาชิก --}}
                        @if(Auth::user()->role_id == 1)
                            <li class="nav-item">
                                {{-- ลิงก์ไปยัง Route ของ Admin --}}
                                <a class="nav-link fw-bold text-warning" href="{{ route('admin.users') }}">
                                    👥 จัดการสมาชิก (Admin)
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                สวัสดี, {{ Auth::user()->username }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item text-danger"
                                   href="{{ route('logout') }}"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    🚪 ออกจากระบบ
                                </a>
                                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @yield('content')
    </div>

</div>
</body>
</html>