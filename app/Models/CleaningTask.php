<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningTask extends Model
{
    protected $fillable = [
        'task_name',
        'area',
        'task_date',
        'assigned_to',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'task_date' => 'date',
        ];
    }

    public function activities()
    {
        return $this->hasMany(CleaningActivity::class);
    }
}
