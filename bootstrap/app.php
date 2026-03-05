<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthorMiddleware;

// 📌 ส่วนที่เพิ่มเข้ามา: เรียกใช้งานคลาสสำหรับจัดการ Error ของระบบและเซสชัน
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'  => AdminMiddleware::class,
            'author' => AuthorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
        // 📌 ส่วนที่เพิ่มเข้ามา: ดักจับตอนไม่ได้ล็อกอิน (หรือเซสชันหลุดแล้วพยายามเปลี่ยนหน้า)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            // ถ้าเป็น API ให้ส่ง JSON ถ้าเป็นเว็บให้รีไดเรกต์ไปหน้า login
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('login'))->with('error_timeout', 'การเข้าสู่ระบบหลุดแล้ว กรุณาล็อกอินใหม่ครับ');
        });

        // 📌 ส่วนที่เพิ่มเข้ามา: ดักจับ Error 419 Page Expired (เปิดหน้าเว็บทิ้งไว้นานๆ แล้วกดส่งฟอร์ม)
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            return redirect()->route('login')->with('error_timeout', 'เซสชันหมดอายุ (ทิ้งหน้านี้นานเกินไป) กรุณาล็อกอินใหม่ครับ');
        });

    })->create();