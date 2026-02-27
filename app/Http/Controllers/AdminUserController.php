<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        // middleware admin คุมแล้ว ไม่ต้องเช็คซ้ำ
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
    // ห้าม admin แก้ role ตัวเอง เพิ่อจะได้ไม่ต้องไปแก้Roleตัวเองคืนในsql
    if (auth()->id() == $id) {
        return back()->with('error', 'ไม่สามารถเปลี่ยนสิทธิ์ของตัวเองได้');
    }

    $request->validate([
        'role_id' => 'required|in:1,2,3',
    ]);

    $user = User::findOrFail($id);
    $user->role_id = $request->role_id;
    $user->save();

    return back()->with('success', 'อัปเดตสิทธิ์เรียบร้อย');
    }

}
