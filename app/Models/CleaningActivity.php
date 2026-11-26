<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningActivity extends Model
{
    protected $fillable = [
        'cleaning_task_id',
        'activity_name',
        'activity_date',
        'description',
        'tanggal',
        'jenis_aktivitas',
        'area',
        'employee_name',
        'status',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'datetime',
            'tanggal' => 'date',
        ];
    }

    public function cleaningTask()
    {
        return $this->belongsTo(CleaningTask::class);
    }
}
