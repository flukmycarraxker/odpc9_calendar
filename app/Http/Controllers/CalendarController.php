<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\Department;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        $firstDayOfMonth = Carbon::create($year, $month, 1);
        $startDay    = $firstDayOfMonth->dayOfWeek;
        $daysInMonth = $firstDayOfMonth->daysInMonth;

        $meetings = Meeting::with('department')
            ->whereMonth('meeting_date', $month)
            ->whereYear('meeting_date', $year)
            ->get()
            ->groupBy(fn ($m) => $m->meeting_date->format('Y-m-d'));

        $departments = Department::all();

        return view('calendar.index', compact(
            'month',
            'year',
            'startDay',
            'daysInMonth',
            'meetings',
            'departments'
        ));
    }
}