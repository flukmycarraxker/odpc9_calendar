<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $table = 'meetings';
    protected $primaryKey = 'meeting_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'meeting_title',
        'meeting_date',
        'start_time',
        'end_time',
        'meeting_period' ,
        'location_name',
        'department_id',
        'people_num',
        'admin_id',
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'people_num'   => 'integer',
    ];

    /**
     * ความสัมพันธ์กับแผนก
     */
    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'department_id'
        );
    }
}