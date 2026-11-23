<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DutyRoster extends Model
{
    protected $fillable = [
        'period',
        'start_date',
        'end_date',
        'member_1_id',
        'member_2_id',
        'member_3_id',
        'member_4_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'period' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function member1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_1_id');
    }

    public function member2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_2_id');
    }

    public function member3(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_3_id');
    }

    public function member4(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_4_id');
    }
}
