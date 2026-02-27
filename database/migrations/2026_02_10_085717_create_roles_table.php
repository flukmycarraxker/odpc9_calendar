<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- อย่าลืม use DB ตรงนี้ด้วยนะครับ สำคัญมาก!

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. สร้างตาราง roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->timestamps();
        });

        // 2. Insert ข้อมูลเริ่มต้นเข้าไปในตารางทันทีที่สร้างเสร็จ
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'admin',
                'label' => 'ผู้ดูแลระบบ (Admin)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'user',
                'label' => 'ผู้ใช้งานทั่วไป (User)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'guest',
                'label' => 'ผู้เยี่ยมชม (Guest)',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};