<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        // เช็คว่ากดปุ่ม "วันนี้" (มี date) หรือกดเปลี่ยน "เดือน/ปี" (มี month, year)
        if ($request->has('date')) {
            $current = Carbon::parse($request->date);
        } elseif ($request->has('month') && $request->has('year')) {
            $current = Carbon::create($request->year, $request->month, 1);
        } else {
            $current = Carbon::today(); // ถ้าเพิ่งเข้าเว็บครั้งแรก ให้ใช้วันนี้
        }

        $year  = $current->year;
        $month = $current->month;

        // คำนวณวันแรกของเดือน, จำนวนวันในเดือน, และวันเริ่มของสัปดาห์
        $firstDay    = Carbon::create($year, $month, 1);
        $daysInMonth = $firstDay->daysInMonth;
        $startDay    = $firstDay->dayOfWeek;

        // ดึงข้อมูลการประชุมเฉพาะของเดือนและปีที่กำลังดูอยู่
        $meetings = Meeting::whereYear('meeting_date', $year)
            ->whereMonth('meeting_date', $month)
            ->with(['department', 'user']) // 📌 เพิ่ม 'user' ตรงนี้จุดเดียวครับ
            ->get()
            ->groupBy(fn ($m) => $m->meeting_date->format('Y-m-d'));

        $departments = Department::all();

        return view('calendar.index', compact(
            'meetings',
            'year',
            'month',
            'daysInMonth',
            'startDay',
            'departments'
        ));
    }

    // ================== CREATE ==================
    public function store(Request $request)
    {
        $request->validate([
            'meeting_title'  => 'required|string',
            'meeting_date'   => 'required|date',
            'meeting_period' => 'required|in:morning,afternoon,full',
            'location_name'  => 'required|string',
            'department_id'  => 'required|integer',
            'people_num'     => 'required|integer|min:1',
            'start_time'     => 'required',
            'start_min'      => 'required',
            'end_time'       => 'required',
            'end_min'        => 'required',
        ]);

        Meeting::create([
            'meeting_title'  => $request->meeting_title,
            'meeting_date'   => $request->meeting_date,
            'start_time'     => "{$request->start_time}:{$request->start_min}:00",
            'end_time'       => "{$request->end_time}:{$request->end_min}:00",
            'meeting_period' => $request->meeting_period,
            'location_name'  => $request->location_name,
            'department_id'  => $request->department_id,
            'people_num'     => $request->people_num,
            'admin_id'       => auth()->id(),
        ]);

        // กลับไปหน้าปฏิทินของเดือน/ปี ที่เพิ่งเพิ่มการประชุมลงไป พร้อมส่งค่า success ไปให้ SweetAlert2
        $date = Carbon::parse($request->meeting_date);
        return redirect('/?month=' . $date->month . '&year=' . $date->year)->with('success', 'เพิ่มการประชุมเรียบร้อย');
    }

    // ================== UPDATE ==================
    public function update(Request $request, Meeting $meeting)
    {
        // 📌 เพิ่มโค้ดเช็คสิทธิ์: ถ้าคนที่ล็อกอิน ไม่ใช่คนที่สร้างข้อมูลนี้ จะไม่อนุญาตให้แก้ไข
        if ($meeting->admin_id !== auth()->id()) {
            return back()->with('error', 'คุณไม่มีสิทธิ์แก้ไขการประชุมของผู้อื่น');
        }

        $request->validate([
            'meeting_title'  => 'required|string',
            'meeting_date'   => 'required|date',
            'meeting_period' => 'required|in:morning,afternoon,full',
            'location_name'  => 'required|string',
            'department_id'  => 'required|integer',
            'people_num'     => 'required|integer|min:1',
            'start_time'     => 'required',
            'start_min'      => 'required',
            'end_time'       => 'required',
            'end_min'        => 'required',
        ]);

        $meeting->update([
            'meeting_title'  => $request->meeting_title,
            'meeting_date'   => $request->meeting_date,
            'start_time'     => "{$request->start_time}:{$request->start_min}:00",
            'end_time'       => "{$request->end_time}:{$request->end_min}:00",
            'meeting_period' => $request->meeting_period,
            'location_name'  => $request->location_name,
            'department_id'  => $request->department_id,
            'people_num'     => $request->people_num,
        ]);

        // รีเฟรชหน้าเดิม พร้อมส่งแจ้งเตือน
        return back()->with('success', 'แก้ไขการประชุมเรียบร้อย');
    }

    // ================== DELETE ==================
    public function destroy(Meeting $meeting)
    {
        // 📌 เพิ่มโค้ดเช็คสิทธิ์: ถ้าคนที่ล็อกอิน ไม่ใช่คนที่สร้างข้อมูลนี้ จะไม่อนุญาตให้ลบ
        if ($meeting->admin_id !== auth()->id()) {
            return back()->with('error', 'คุณไม่มีสิทธิ์ลบการประชุมของผู้อื่น');
        }

        $meeting->delete();

        // รีเฟรชหน้าเดิม พร้อมส่งแจ้งเตือนลบสำเร็จ
        return back()->with('success', 'ลบการประชุมเรียบร้อย');
    }
}