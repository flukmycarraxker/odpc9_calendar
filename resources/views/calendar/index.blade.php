@extends('layouts.app')

@section('title', 'ปฏิทินกิจกรรม')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #1e3c72, #2fd4b3);
        min-height: 100vh;
    }

    .calendar-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 4px 1px;
    }

    .calendar-box {
        width: 100%;
        max-width: 1000px;
        background: rgb(255, 255, 255);
        backdrop-filter: blur(14px);
        padding: 25px;
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(0,0,0,.25);
        color: #000000;
        /* ป้องกันตารางล้นกล่อง */
        overflow-x: auto; 
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header h2 {
        margin: 0;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .header a {
        text-decoration: none;
        background: #86abac;
        color: #000000;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 14px;
        transition: .3s;
    }

    .header a:hover {
        background: rgba(20, 184, 166, 0.85);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        color: #000000;
        /* ป้องกันตารางบีบตัวจนพังบนจอมือถือมากเกินไป */
        min-width: 500px; 
    }

    th {
        background: rgba(20, 184, 166, 0.85);
        padding: 10px;
        text-align: center;
        font-weight: 500;
    }

    td {
        border: 1px solid rgb(0, 0, 0);
        height: 120px;
        vertical-align: top;
        padding: 6px;
        font-size: 14px;
        overflow: hidden;
        position: relative;
    }

    .day {
        font-weight: bold;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .today {
        background: rgba(61, 61, 61, 0.623);
        border: 2px solid rgba(61, 61, 61, 0.623);
    }  

    .btn-today {
        text-decoration: none;
        background: #00aeff;
        color: #ffffff !important;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0, 174, 255, 0.2);
        transition: 0.3s ease;
        display: flex;
        align-items: center;
        margin: 0 !important;
        white-space: nowrap;
    }
    
    .btn-today:hover {
        background: #008bcc;
        transform: translateY(-2px);
    }

    .top-actions {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    td a {
        color: #000000;
        text-decoration: none;
    }

    td a:hover {
        text-decoration: underline;
    }

    .add-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #00aeff;
        color: #fff;
        font-size: 22px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        opacity: 0;
        transition: .2s ease;
    }

    td:hover .add-btn {
        opacity: 1;
    }

    .add-btn:hover {
        background: #0095da;
    }

    .date-picker {
        height: 38px;
        padding: 6px 14px;
        font-size: 14px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        width: 160px;
        outline: none;
        transition: 0.3s;
        cursor: pointer;
        background-color: #f8fafc;
    }

    /* ===== สีช่วงการประชุม ===== */
    .event {
        padding: 3px 6px;
        margin-bottom: 4px;
        border-radius: 4px;
        font-size: 12px;
    }

    .event.morning, .meeting-btn.morning {
        background: #38ff38;
        border: none;
        width: 100%;
        text-align: left;
        border-radius: 4px;
        /* ป้องกันข้อความล้นปุ่ม */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .event.afternoon, .meeting-btn.afternoon {
        background: #0066ff;
        color: #fff;
        border: none;
        width: 100%;
        text-align: left;
        border-radius: 4px;
        /* ป้องกันข้อความล้นปุ่ม */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .event.full, .meeting-btn.full {
        background: #ff0000;
        color: #fff;
        border: none;
        width: 100%;
        text-align: left;
        border-radius: 4px;
        /* ป้องกันข้อความล้นปุ่ม */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ===== ปุ่ม "อีก X รายการ" ===== */
    .more-btn {
        background: none;
        border: none;
        color: #000000;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
        padding: 2px 4px;
        margin-top: 4px;
        text-align: left;
        width: 100%;
        transition: 0.2s;
    }
    
    .more-btn:hover {
        text-decoration: underline;
        color: #00aeff;
    }

    /* ===== ตกแต่ง Popup สีดำ ===== */
    .dark-modal .modal-content {
        background-color: #2b2b2b;
        color: #ffffff;
        border-radius: 16px;
        padding: 10px;
    }
    .dark-modal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* ===== ปุ่มค้นหาวันที่ (สไตล์ใหม่) ===== */
    .btn-search-date {
        background: #ffffff;
        color: #333333;
        border: 2px solid #cbd5e1;
        padding: 7px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .btn-search-date:hover {
        background: #f8fafc;
        border-color: #00aeff; 
        color: #00aeff;
        transform: translateY(-2px); 
    }

    /* 📌 ===== ส่วนอธิบายสัญลักษณ์ (Legend) โค้ดที่เพิ่มใหม่ ===== 📌 */
    .calendar-legend {
        margin-top: 25px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: bold;
        color: #000000;
    }

    .legend-color-box {
        width: 45px;
        height: 20px;
        border: 1px solid #000000;
    }

    /* =========================================
       📱 Responsive Design (รองรับมือถือ)
    ========================================= */
    @media (max-width: 768px) {
        .calendar-wrapper { padding: 10px; }
        .calendar-box { padding: 15px; border-radius: 14px; }
        .header h2 { font-size: 18px; }
        .header a { font-size: 13px; padding: 5px 12px; }
        th { font-size: 11px; padding: 6px 0; }
        td { height: 80px; padding: 4px; font-size: 10px; }
        .day { font-size: 12px; margin-bottom: 2px; }
        
        .event { padding: 2px 4px; margin-bottom: 2px; font-size: 10px; }
        .meeting-btn { font-size: 10px; padding: 2px; }
        .more-btn { font-size: 10px; padding: 0 2px; }
        
        .add-btn { width: 28px; height: 28px; font-size: 18px; }

        /* ปรับส่วนคำอธิบายสัญลักษณ์ในมือถือ */
        .calendar-legend { gap: 15px; }
        .legend-item { font-size: 12px; }
        .legend-color-box { width: 35px; height: 16px; }
    }

    @media (max-width: 576px) {
        /* ปรับส่วนหัวให้ซ้อนกัน */
        .top-actions {
            flex-direction: column;
            align-items: stretch;
        }
        .btn-today, .btn-search-date {
            justify-content: center;
        }
        
        /* Modal Footer ปรับปุ่มให้เต็มจอ */
        #detailMeetingModal .modal-footer {
            flex-direction: column;
            gap: 10px;
        }
        #detailMeetingModal .modal-footer form, 
        #detailMeetingModal .modal-footer button {
            width: 100%;
        }
        #deleteMeetingForm button {
            width: 100%;
        }
    }
</style>

<div class="calendar-wrapper">
    <div class="calendar-box">

        <div class="header">
            <a href="/?month={{ $month == 1 ? 12 : $month - 1 }}&year={{ $month == 1 ? $year - 1 : $year }}">◀</a>
            <h3>{{ \Carbon\Carbon::create($year, $month)->translatedFormat('F') }} {{ $year + 543 }}</h3>
            <a href="/?month={{ $month == 12 ? 1 : $month + 1 }}&year={{ $month == 12 ? $year + 1 : $year }}">▶</a>
        </div>

       <div class="top-actions">
            <a href="/?date={{ now()->format('Y-m-d') }}" class="btn-today">
                วันนี้
            </a>
            <button type="button" id="jumpDateBtn" class="btn-search-date">
                🗓️ เลือกวันที่จากปฏิทิน...
            </button>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>อาทิตย์</th><th>จันทร์</th><th>อังคาร</th><th>พุธ</th><th>พฤหัสบดี</th><th>ศุกร์</th><th>เสาร์</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @php
                        $day = 1;
                        $today = now()->format('Y-m-d');
                    @endphp

                    @for ($i = 0; $i < $startDay; $i++)
                        <td></td>
                    @endfor

                    @while ($day <= $daysInMonth)
                        @php
                            $date = \Carbon\Carbon::create($year,$month,$day)->format('Y-m-d');
                        @endphp

                        <td class="{{ $date == $today ? 'today' : '' }}">
                            <div class="day">{{ $day }}</div>

                            {{-- ปุ่มเพิ่มการประชุม --}}
                            @auth
                                <button
                                    type="button"
                                    class="add-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#meetingModal"
                                    data-date="{{ $date }}"
                                    title="เพิ่มการประชุม">
                                    +
                                </button>
                            @endauth

                            {{-- แสดงรายการประชุม (จำกัด 3 รายการ) --}}
                            @if(isset($meetings[$date]))
                                @php
                                    $dailyMeetings = $meetings[$date];
                                    $totalMeetings = count($dailyMeetings);
                                    $limit = 3; 
                                @endphp

                                @foreach(collect($dailyMeetings)->take($limit) as $m)
                                    <div class="event">
                                        <button
                                            type="button"
                                            class="meeting-btn {{ $m->meeting_period }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailMeetingModal"
                                            data-id="{{ $m->meeting_id }}"
                                            data-title="{{ $m->meeting_title }}"
                                            data-date="{{ $m->meeting_date->format('Y-m-d') }}"
                                            data-start="{{ substr($m->start_time,0,5) }}"
                                            data-end="{{ substr($m->end_time,0,5) }}"
                                            data-location="{{ $m->location_name }}"
                                            data-department="{{ $m->department->name ?? '-' }}"
                                            data-department-id="{{ $m->department_id }}" 
                                            data-people="{{ $m->people_num }}"
                                            data-period="{{ $m->meeting_period }}"
                                        >
                                            {{ $m->meeting_title }}
                                        </button>
                                    </div>
                                @endforeach

                                @if($totalMeetings > $limit)
                                    <button type="button" class="more-btn" data-bs-toggle="modal" data-bs-target="#moreModal-{{ $date }}">
                                        อีก {{ $totalMeetings - $limit }} รายการ
                                    </button>
                                @endif
                            @endif
                        </td>

                        @if (($day + $startDay) % 7 == 0)
                            </tr><tr>
                        @endif

                        @php $day++; @endphp
                    @endwhile
                </tr>
            </tbody>
        </table>

        {{-- 📌 ===== ส่วนคำอธิบายสัญลักษณ์ที่เพิ่มเข้ามาใหม่ (อยู่ใต้ตาราง) ===== 📌 --}}
        <div class="calendar-legend">
            <div class="legend-item">
                <div class="legend-color-box" style="background-color: #38ff38;"></div>
                <span>มีการประชุมช่วงเช้า</span>
            </div>
            <div class="legend-item">
                <div class="legend-color-box" style="background-color: #0066ff;"></div>
                <span>มีการประชุมช่วงบ่าย</span>
            </div>
            <div class="legend-item">
                <div class="legend-color-box" style="background-color: #ff0000;"></div>
                <span>มีการประชุมทั้งวัน</span>
            </div>
            <div class="legend-item">
                <div class="legend-color-box" style="background-color: #8a8a8a;"></div>
                <span>วันที่ปัจจุบัน</span>
            </div>
        </div>

    </div>
</div>

{{-- ================= MODAL "อีก X รายการ" (Popup สีดำ) ================= --}}
@foreach($meetings as $date => $dailyMeetings)
    @if(count($dailyMeetings) > 3) 
        <div class="modal fade dark-modal" id="moreModal-{{ $date }}" tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered"> 
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0 justify-content-center position-relative">
                        @php
                            $carbonDate = \Carbon\Carbon::parse($date);
                            $thaiDays = ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'];
                            $dayName = $thaiDays[$carbonDate->dayOfWeek];
                        @endphp
                        
                        <div class="text-center">
                            <div style="font-size: 14px; color: #aaaaaa;">{{ $dayName }}</div>
                            <h2 class="mb-0" style="font-weight: bold;">{{ $carbonDate->format('d') }}</h2>
                        </div>
                        
                        <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3" data-bs-dismiss="modal" style="z-index: 1050; cursor: pointer;"></button>
                    </div>

                    <div class="modal-body pt-3">
                        @foreach($dailyMeetings as $m)
                            <div class="event mb-2">
                                <button
                                    type="button"
                                    class="meeting-btn {{ $m->meeting_period }} w-100"
                                    style="padding: 8px; font-size: 14px;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailMeetingModal"
                                    data-id="{{ $m->meeting_id }}"
                                    data-title="{{ $m->meeting_title }}"
                                    data-date="{{ $m->meeting_date->format('Y-m-d') }}"
                                    data-start="{{ substr($m->start_time,0,5) }}"
                                    data-end="{{ substr($m->end_time,0,5) }}"
                                    data-location="{{ $m->location_name }}"
                                    data-department="{{ $m->department->name ?? '-' }}"
                                    data-department-id="{{ $m->department_id }}" 
                                    data-people="{{ $m->people_num }}"
                                    data-period="{{ $m->meeting_period }}"
                                >
                                    {{ $m->meeting_title }}
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- ================= MODAL เพิ่มการประชุม ================= --}}
<div class="modal fade" id="meetingModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">เพิ่มการประชุม</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('meetings.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>ชื่อการประชุม</label>
                <input type="text" name="meeting_title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>วันที่</label>
                <input type="text" id="meeting_date_display" class="form-control" readonly>
                <input type="hidden" name="meeting_date" id="meeting_date">
            </div>

            <div class="mb-3">
                <label class="form-label">เวลา</label>
                <div class="row">
                    <div class="col">
                        <label class="form-label small">เริ่มประชุม</label>
                        <div class="d-flex gap-2">
                            <select name="start_time" class="form-select" required>
                                @for ($h = 0; $h < 24; $h++)
                                    <option value="{{ sprintf('%02d', $h) }}">{{ sprintf('%02d', $h) }}</option>
                                @endfor
                            </select>
                            <select name="start_min" class="form-select" required>
                                @for ($m = 0; $m < 60; $m += 5)
                                    <option value="{{ sprintf('%02d', $m) }}">{{ sprintf('%02d', $m) }}</option>
                                @endfor
                            </select>
                        </div>
                        <small class="text-muted">ชั่วโมง : นาที</small>
                    </div>

                    <div class="col">
                        <label class="form-label small">สิ้นสุดประชุม</label>
                        <div class="d-flex gap-2">
                            <select name="end_time" class="form-select" required>
                                @for ($h = 0; $h < 24; $h++)
                                    <option value="{{ sprintf('%02d', $h) }}">{{ sprintf('%02d', $h) }}</option>
                                @endfor
                            </select>
                            <select name="end_min" class="form-select" required>
                                @for ($m = 0; $m < 60; $m += 5)
                                    <option value="{{ sprintf('%02d', $m) }}">{{ sprintf('%02d', $m) }}</option>
                                @endfor
                            </select>
                        </div>
                        <small class="text-muted">ชั่วโมง : นาที</small>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">ช่วงการประชุม</label>
                <div class="d-flex gap-3">
                    <label class="d-flex align-items-center gap-2">
                        <input type="radio" name="meeting_period" value="morning" required>
                        <span class="badge bg-success">เช้า</span>
                    </label>
                    <label class="d-flex align-items-center gap-2">
                        <input type="radio" name="meeting_period" value="afternoon">
                        <span class="badge bg-primary">บ่าย</span>
                    </label>
                    <label class="d-flex align-items-center gap-2">
                        <input type="radio" name="meeting_period" value="full">
                        <span class="badge bg-danger">ทั้งวัน</span>
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label>ชื่อสถานที่</label>
                <input type="text" name="location_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">แผนก</label>
                <select name="department_id" class="form-select" required>
                    <option value="">-- เลือกแผนก --</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->department_id }}" style="color: {{ $department->color_code }}">
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">จำนวนคนที่ไป</label>
                <input type="number" name="people_num" class="form-control" min="1" max="500" placeholder="กรอกจำนวนคน" required>
            </div>

            <button type="submit" class="btn btn-primary">เพิ่มการประชุม</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- ================= MODAL แก้ไขการประชุม ================= --}}
<div class="modal fade" id="editMeetingModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" id="editMeetingForm">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">แก้ไขการประชุม</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label>ชื่อการประชุม</label>
            <input type="text" name="meeting_title" id="edit_title" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>วันที่</label>
            <input type="date" name="meeting_date" id="edit_date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">เวลา</label>
            <div class="row">
                <div class="col">
                    <label class="form-label small">เริ่มประชุม</label>
                    <div class="d-flex gap-2">
                        <select name="start_time" id="edit_start_h" class="form-select" required>
                            @for ($h = 0; $h < 24; $h++)
                                <option value="{{ sprintf('%02d', $h) }}">{{ sprintf('%02d', $h) }}</option>
                            @endfor
                        </select>
                        <select name="start_min" id="edit_start_m" class="form-select" required>
                            @for ($m = 0; $m < 60; $m += 5)
                                <option value="{{ sprintf('%02d', $m) }}">{{ sprintf('%02d', $m) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label class="form-label small">สิ้นสุดประชุม</label>
                    <div class="d-flex gap-2">
                        <select name="end_time" id="edit_end_h" class="form-select" required>
                            @for ($h = 0; $h < 24; $h++)
                                <option value="{{ sprintf('%02d', $h) }}">{{ sprintf('%02d', $h) }}</option>
                            @endfor
                        </select>
                        <select name="end_min" id="edit_end_m" class="form-select" required>
                            @for ($m = 0; $m < 60; $m += 5)
                                <option value="{{ sprintf('%02d', $m) }}">{{ sprintf('%02d', $m) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
          </div>

          <div class="mb-3">
              <label class="form-label">ช่วงการประชุม</label>
              <div class="d-flex gap-3">
                  <label class="d-flex align-items-center gap-2">
                      <input type="radio" name="meeting_period" value="morning" required>
                      <span class="badge bg-success">เช้า</span>
                  </label>
                  <label class="d-flex align-items-center gap-2">
                      <input type="radio" name="meeting_period" value="afternoon">
                      <span class="badge bg-primary">บ่าย</span>
                  </label>
                  <label class="d-flex align-items-center gap-2">
                      <input type="radio" name="meeting_period" value="full">
                      <span class="badge bg-danger">ทั้งวัน</span>
                  </label>
              </div>
          </div>

          <div class="mb-3">
            <label>สถานที่</label>
            <input type="text" name="location_name" id="edit_location" class="form-control">
          </div>

          <div class="mb-3">
            <label>แผนก</label>
            <select name="department_id" id="edit_department" class="form-select">
              @foreach($departments as $d)
                <option value="{{ $d->department_id }}">{{ $d->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label>จำนวนคน</label>
            <input type="number" name="people_num" id="edit_people" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
        </div>

      </form>

    </div>
  </div>
</div>

{{-- ================= MODAL รายละเอียด (แสดงตอนแรก) ================= --}}
<div class="modal fade" id="detailMeetingModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="detail_title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p><b>วันที่:</b> <span id="detail_date"></span></p>
        <p><b>เวลา:</b> <span id="detail_time"></span></p>
        <p><b>สถานที่:</b> <span id="detail_location"></span></p>
        <p><b>แผนก:</b> <span id="detail_department"></span></p>
        <p><b>จำนวนคน:</b> <span id="detail_people"></span></p>
        <p><b>ช่วงเวลา:</b> <span id="detail_period"></span></p>
      </div>

      @auth
      <div class="modal-footer d-flex justify-content-between w-100">
        
        <form method="POST" id="deleteMeetingForm">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                🗑️ ลบข้อมูล
            </button>
        </form>

        <button type="button" class="btn btn-warning" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editMeetingModal">
            ✏️ แก้ไข
        </button>
        
      </div>
      @endauth

    </div>
  </div>
</div>

{{-- ===== เพิ่ม Library พื้นฐาน ===== --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>

{{-- 📌 โหลด SweetAlert2 CDN 📌 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const periodMap = {
        morning: 'เช้า',
        afternoon: 'บ่าย',
        full: 'ทั้งวัน'
    };

    /* ===============================
       1. ระบบเติมข้อมูล 
    =============================== */
    document.querySelectorAll('.meeting-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            document.getElementById('detail_title').innerText      = this.dataset.title;
            document.getElementById('detail_date').innerText       = this.dataset.date;
            document.getElementById('detail_time').innerText       = this.dataset.start + ' - ' + this.dataset.end;
            document.getElementById('detail_location').innerText   = this.dataset.location;
            document.getElementById('detail_department').innerText = this.dataset.department;
            document.getElementById('detail_people').innerText     = this.dataset.people;
            document.getElementById('detail_period').innerText     = periodMap[this.dataset.period] ?? '-';

            const [sh, sm] = this.dataset.start.split(':');
            const [eh, em] = this.dataset.end.split(':');

            document.getElementById('editMeetingForm').action = `/meetings/${this.dataset.id}`;
            document.getElementById('deleteMeetingForm').action = `/meetings/${this.dataset.id}`;
            
            document.getElementById('edit_title').value    = this.dataset.title;
            document.getElementById('edit_date').value     = this.dataset.date;
            document.getElementById('edit_location').value = this.dataset.location;
            document.getElementById('edit_people').value   = this.dataset.people;
            document.getElementById('edit_start_h').value = sh;
            document.getElementById('edit_start_m').value = sm;
            document.getElementById('edit_end_h').value   = eh;
            document.getElementById('edit_end_m').value   = em;

            const editDepartment = document.getElementById('edit_department');
            if (editDepartment && this.dataset.departmentId) {
                editDepartment.value = this.dataset.departmentId;
            }

            document.querySelectorAll('#editMeetingModal input[name="meeting_period"]').forEach(radio => {
                radio.checked = (radio.value === this.dataset.period);
            });

            /* ===== ระบบสลับหน้าต่าง (ดำ -> ขาว) ===== */
            const parentDarkModal = this.closest('.dark-modal');
            const detailModalEl = document.getElementById('detailMeetingModal');
            const detailModal = bootstrap.Modal.getOrCreateInstance(detailModalEl);

            if (parentDarkModal) {
                const darkModalInstance = bootstrap.Modal.getInstance(parentDarkModal);
                if (darkModalInstance) {
                    darkModalInstance.hide();
                }
                setTimeout(() => {
                    detailModal.show();
                }, 400); 
            } else {
                detailModal.show();
            }
        });
    });

    /* ===============================
       2. กดปุ่ม + เพื่อเพิ่มการประชุม
    =============================== */
    const meetingModal = document.getElementById('meetingModal');
    if (meetingModal) {
        meetingModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const date   = button.getAttribute('data-date');
            if(date) {
                document.getElementById('meeting_date').value = date;
                document.getElementById('meeting_date_display').value = date;
            }
        });
    }

    /* ===============================
       3. ระบบกระโดดไปวันที่/เดือนที่เลือก (Flatpickr) <h3>{{ \Carbon\Carbon::create($year, $month)->translatedFormat('F') }} {{ $year + 543 }}</h3>
    =============================== */
    const jumpDateBtn = document.getElementById('jumpDateBtn');
    if (jumpDateBtn) {
        flatpickr("#jumpDateBtn", {
            locale: "th", 
            dateFormat: "Y-m-d", 
            
            // 📌 1. เมื่อปฏิทินโหลดเสร็จ ให้เปลี่ยนเลขปีเป็น พ.ศ. ทันที
            onReady: function(selectedDates, dateStr, instance) {
                if (instance.currentYearElement) {
                    instance.currentYearElement.value = instance.currentYear + 543;
                }
            },
            
            // 📌 2. เมื่อผู้ใช้กดเปลี่ยนปี (ลูกศรขึ้น/ลง) ให้คำนวณเป็น พ.ศ. ใหม่
            onYearChange: function(selectedDates, dateStr, instance) {
                if (instance.currentYearElement) {
                    instance.currentYearElement.value = instance.currentYear + 543;
                }
            },
            
            // 📌 3. เมื่อเปลี่ยนเดือน ป้องกันไม่ให้เลขปีเด้งกลับไปเป็น ค.ศ.
            onMonthChange: function(selectedDates, dateStr, instance) {
                if (instance.currentYearElement) {
                    instance.currentYearElement.value = instance.currentYear + 543;
                }
            },

            onChange: function(selectedDates, dateStr, instance) {
                // พอกดเลือกวันในปฏิทินปุ๊บ ระบบจะเปลี่ยนหน้าทันที
                if (dateStr) {
                    window.location.href = `/?date=${dateStr}`;
                }
            }
        });
    }

});

/* ===============================
   📌 4. ฟังก์ชันยืนยันการลบ (SweetAlert2)
=============================== */
function confirmDelete() {
    Swal.fire({
        title: 'ยืนยันการลบข้อมูล?',
        text: "คุณแน่ใจหรือไม่ว่าต้องการลบการประชุมนี้? (ข้อมูลลบแล้วกู้คืนไม่ได้!)",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // ถ้ากด "ใช่" ให้ทำการรันฟอร์มลบข้อมูล
            document.getElementById('deleteMeetingForm').submit();
        }
    });
}

/* ===============================
   📌 5. เด้งแจ้งเตือนเมื่อทำงานสำเร็จ (SweetAlert2)
=============================== */
// อ่านค่า Session ถ้ามีการตั้งค่า status หรือ success มาจาก Controller
@if(session('status') || session('success'))
    Swal.fire({
        icon: 'success',
        title: 'สำเร็จ!',
        text: '{!! session('status') ?? session('success') !!}',
        toast: true,
        position: 'top-end', // ให้เด้งมุมขวาบน
        showConfirmButton: false,
        timer: 3000, // โชว์ 3 วินาที
        timerProgressBar: true
    });
@endif
</script>

@endsection