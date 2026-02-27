<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id('meeting_id');

            $table->text('meeting_title');
            $table->date('meeting_date');

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->text('location_name')->nullable();

            // FK departments
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')
                ->references('department_id')
                ->on('departments')
                ->nullOnDelete();

            // FK users
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->integer('people_num')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};