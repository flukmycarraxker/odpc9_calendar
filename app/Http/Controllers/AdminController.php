<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }   

    public function store(Request $request) 
    {
        $path = $request->file('img')->store('images');
        dd($path);
    }

    function blog(){
        $blogs=Blog::paginate(3);
        return view('blog',compact('blogs'));
    }

    public function users()
{
    // กันไม่ให้ admin คนอื่นเข้า
    if (Auth::user()->Admin_ID != 1) {
        abort(403);
    }

    $users = User::where('Admin_ID', '!=', 1)->get();

    return view('admin.users', compact('users'));
}

public function changeRole($id)
{
    if (Auth::user()->Admin_ID != 1) {
        abort(403);
    }

    $user = User::findOrFail($id);
    $user->Role = $user->Role === 'admin' ? 'user' : 'admin';
    $user->save();

    return back()->with('success', 'เปลี่ยนสิทธิ์เรียบร้อย');
}


    }

    function create(){
        return view('form');
    }

    function insert(Request $request){
        $request->validate
        ([
            'title'=>'required|max:80',
            'content'=>'required',
            'img'=>'image|mimes:jpeg,png,jpg,webp|max:2048'
        ],
        [
           'title.required'=>'กรุณาป้อนชื่อหัวข้อด้วยครับ',
           'title.max'=>'หัวข้อห้ามเกิน 80 ตัวอักษร', 
           'content.required'=>'กรุณาป้อนเนื้อหาด้วยครับ',
           'img.image'=>'ไฟล์ที่อัพโหลดต้องเป็นรูปภาพเท่านั้น',
        ]
    );

        $image_path = null;
    if ($request->hasFile('img')) {
        $path = $request->file('img')->store('images', 'public'); 
        $image_path = 'storage/' . $path; 
    }

    $data = [
        'title' => $request->title,
        'content' => $request->content,
        'image' => $image_path ,
        'status' => true
    ];

        Blog::insert($data);
        return redirect('/author/blog');
    }
    function delete($id){
        Blog::find($id)->delete();
        return redirect()->back();
    }  
    function change($id){
        $blog=Blog::find($id);
        //dd(!$blog->status);
        $data=[
            'status'=>!$blog->status
        ];
        $blog=Blog::find($id)->update($data);
        return redirect()->back();
    }

    function edit($id){
        $blog=Blog::find($id);
        return view('edit',compact('blog'));
    }
    function update(Request $request, $id) { // รับ $id มาจาก URL แล้ว
    $request->validate([
        'title' => 'required|max:80',
        'content' => 'required',
        'img' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
    ], [
        'title.required' => 'กรุณาป้อนชื่อหัวข้อด้วยครับ',
        'title.max' => 'หัวข้อห้ามเกิน 80 ตัวอักษร', 
        'content.required' => 'กรุณาป้อนเนื้อหาด้วยครับ',
        'img.image' => 'ไฟล์ที่อัพโหลดต้องเป็นรูปภาพเท่านั้น',
    ]);

    
    $blog = Blog::find($id);

    $data = [
        'title' => $request->title,
        'content' => $request->content,
        'image' => $blog->image, 
        'status' => true 
    ];

    if ($request->hasFile('img')) {
        //อัปโหลดรูปใหม่
        $path = $request->file('img')->store('images', 'public');
        $data['image'] = 'storage/' . $path; 
    }

    //updateรูป
    Blog::find($id)->update($data);
    return redirect('/author/blog');
    }
}
