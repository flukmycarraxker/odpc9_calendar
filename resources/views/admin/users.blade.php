@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #1e3c72, #2fd4b3);
        min-height: 100vh;
    }

    .admin-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: flex-start; /* จาก center → top */
        justify-content: center;
        padding: 4px 1px;
    }

    .admin-box {
        width: 100%;
        max-width: 1100px;
        background: rgb(255, 255, 255);
        backdrop-filter: blur(14px);
        padding: 30px;
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(0,0,0,.25);
        color: #000000;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .admin-header h3 {
        margin: 0;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .admin-header a {
        text-decoration: none;
        background: #14b8a6;
        color: #000000;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        transition: .3s;
    }

    .admin-header a:hover {
        background: #2dd4bf;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        color: #000000;
    }

    th {
        background: rgba(20, 184, 166, 0.85);
        padding: 12px;
        text-align: center;
        font-weight: 500;
    }

    td {
        border: 1px solid rgba(255,255,255,.35);
        padding: 10px;
        font-size: 14px;
        vertical-align: middle;
    }

    select.form-select {
        background: rgba(0, 0, 0, 0.31);
        color: #000000;
        border: none;
    }

    select.form-select option {
        color: #000;
    }

    .btn-save {
        background: #14b8a6;
        border: none;
        color: #000000;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 14px;
        transition: .3s;
    }

    .btn-save:hover {
        background: #2dd4bf;
    }

    .badge-admin {
        background: #ef4444;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.5);
        color: #000000;
    }
</style>

<div class="admin-wrapper">
    <div class="admin-box">

        <div class="admin-header">
            <h3>จัดการสมาชิก (Admin)</h3>
            <a href="{{ url('/') }}">กลับหน้าหลัก</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>สิทธิ์</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="text-center">{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            {{-- 🔒 ล็อก admin ไม่ให้แก้ role ตัวเอง --}}
                            @if($user->id === auth()->id())
                                <span class="badge-admin">Admin (คุณ)</span>
                            @else
                                {{-- เพิ่ม ID ให้ฟอร์มแต่ละอัน เพื่อให้ JS สั่ง Submit ได้ถูกตัว --}}
                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" id="form-role-{{ $user->id }}">
                                    @csrf
                                    <div class="d-flex gap-2">
                                        <select name="role_id" class="form-select" id="select-role-{{ $user->id }}">
                                            <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Admin</option>
                                            <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>User</option>
                                            <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Guest</option>
                                        </select>

                                        {{-- ปุ่มเรียกฟังก์ชันยืนยัน --}}
                                        <button type="button" class="btn-save" onclick="confirmRoleChange('{{ $user->id }}', '{{ $user->username }}')">
                                            บันทึก
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </td>

                        <td class="text-center">
                            —
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

{{-- 📌 สคริปต์แจ้งเตือน (เอา @push ออกแล้ว จะทำงานได้ทันที) 📌 --}}
<script>
    /* ===================================================
       ฟังก์ชันยืนยันการเปลี่ยนสิทธิ์
    =================================================== */
    function confirmRoleChange(userId, username) {
        // ดึงข้อความของสิทธิ์ที่ถูกเลือกมาแสดงในกล่องเตือน
        let selectElement = document.getElementById('select-role-' + userId);
        let selectedRoleText = selectElement.options[selectElement.selectedIndex].text;

        Swal.fire({
            title: 'ยืนยันการเปลี่ยนสิทธิ์?',
            html: `คุณต้องการเปลี่ยนสิทธิ์ของ <b>${username}</b> ให้เป็น <b>${selectedRoleText}</b> ใช่หรือไม่?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#14b8a6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ใช่, เปลี่ยนเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // ถ้ายืนยัน ให้สั่ง Submit ฟอร์มของคนคนนั้น
                document.getElementById('form-role-' + userId).submit();
            }
        });
    }

    /* ===================================================
       แจ้งเตือนเมื่อบันทึกสิทธิ์สำเร็จ (รับค่า Session)
    =================================================== */
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{!! session('success') !!}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    });
</script>

@endsection