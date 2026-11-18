<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningActivity extends Model
{
    protected $fillable = [
        'cleaning_task_id',
        'employee_name',
        'activity_name',
        'area',
        'description',
        'status',
        'activity_date',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'datetime',
        ];
    }

    public function cleaningTask()
    {
        return $this->belongsTo(CleaningTask::class);
    }
}
