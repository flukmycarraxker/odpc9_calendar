@extends('layouts.app')
@section('title') 
    {{$blog->title}}
@endsection
@section('content')
    @if ($blog->image)
        <div class="text-center mb-3"> <img src="{{ asset($blog->image) }}" class="img-fluid rounded" width="500" alt="{{$blog->title}}">
        </div>  
    @endif
    <h2>{{$blog->title}}</h2>
    <hr>
    <p>{{$blog->content}}</p>
    <a href="/" class="btn btn-secondary mt-3">กลับไปหน้าแรก</a>
@endsection