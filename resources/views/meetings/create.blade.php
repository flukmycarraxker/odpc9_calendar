@php
    use Carbon\Carbon;

    $date = request('date')
        ? Carbon::parse(request('date'))
        : Carbon::today();
@endphp

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มการประชุม</title>

    <style>
        body {
            font-family: sans-serif;
            background: #f7f7f7;
            padding: 30px;
        }
        .card {
            background: #fff;
            max-width: 500px;
            margin: auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background: #2c7be5;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>เพิ่มการประชุม</h2>

    <form action="{{ route('meetings.store') }}" method="POST">
    @csrf

    <label>หัวข้อการประชุม</label>
    <input type="text" name="meeting_title" required>

    <label>วันที่ประชุม</label>
    <input type="date" name="meeting_date"
           value="{{ $date->format('Y-m-d') }}" required>

    <label>เวลาเริ่ม</label>
    <input type="time" name="start_time" required>

    <label>เวลาสิ้นสุด</label>
    <input type="time" name="end_time" required>

    <label>ห้องประชุม</label>
    <input type="text" name="room_name" required>

    <button type="submit">บันทึกการประชุม</button>
</form>


    <a href="/?date={{ $date->format('Y-m-d') }}" class="back">
        ← กลับไปตารางห้องประชุม
    </a>
</div>

</body>
</html>