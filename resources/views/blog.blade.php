@extends('layouts.app')
@section('title', 'ข่าวทั้งหมด')
@section('content')
   @if (count($blogs) > 0)
        <h2 class="text-center">ข่าวทั้งหมด</h2>
        <table class="table  table-bordered text-center">
      <thead>
        <tr>
          <th scope="col">รูปภาพข่าว</th>
          <th scope="col">ชื่อข่าว</th>
          <th scope="col">เนื้อหา</th>
          <th scope="col">สถานะ</th>
          <th scope="col">แก้ไขบทความ</th>
          <th scope="col">ลบข่าว</th>
        </tr>
      </thead>
      <tbody>
            @foreach ($blogs as $item)
                <tr>
                    <td><img src="{{ asset($item->image) }}" alt="Image" width="100"></td>
                    <td>{{ $item->title }}</td>
                    <td>{{ Str::limit($item->content, 10) }}</td>

                    <td>
                        @if($item->status == true)
                            <a href="{{ route('change', $item->id) }}" class="btn btn-success">เผยแพร่</a>
                        @else
                            <a href="{{ route('change', $item->id) }}" class="btn btn-secondary">ไม่เผยแพร่</a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('edit', $item->id) }}" class="btn btn-primary">แก้ไข</a>
                    </td>
                    <td><a href="{{ route('delete', $item->id) }}" class="btn btn-danger"
                        onclick="return confirm('คุณต้องการลบข่าว {{ $item->title }} หรือไม่?')">ลบ</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
   @else
       <h2 class="text text-center py-2">ไม่มีข่าวในระบบ</h2>
   @endif
   {{$blogs->links()}}
@endsection