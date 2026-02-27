@extends('layouts.app')
@section('title', 'เขียนข่าว')
@section('content')
    <h2 class="text text-center py-2">เขียนข่าวใหม่</h2>
    <form method="POST" action="/author/insert" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="title">ชื่อหัวข้อข่าว</label>
            <input type="text" name="title" id="title" class="form-control">
        </div>
        @error('title')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror

        <div class="form-group">
            <label for="content">เนื้อหาข่าว</label>
            <textarea name="content" cols="30" rows="5" class="form-control" id="content">{{ old('content') }}</textarea>
        </div>
        @error('content')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror

        <div class="form-group mt-3">
            <label>อัพโหลดรูปภาพ</label>
            <input type="file" name="img" class="form-control"> <p class="help-block text-muted">กรุณาใส่รูปภาพประกอบข่าว</p>
        </div>

        <input type="submit" value="บันทึกข่าว" class="btn btn-primary my-3">
        <a href="/author/blog" class="btn btn-secondary my-3">ข่าวทั้งหมด</a>
    </form>


    <script>
        ClassicEditor
            .create( document.querySelector('#content') )
            .catch( error => {
                console.error( error );
            } );
    </script>
@endsection