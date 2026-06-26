<?php

namespace App\Domains\CheckIn\Models;

use App\Domains\Couple\Models\Couple;
use App\Models\User;
use Database\Factories\MonthlyCoupleCheckInFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyCoupleCheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'user_id',
        'month',
        'felt_heard',
        'felt_appreciated',
        'conflict_safety',
        'emotional_closeness',
        'submitted_at',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): MonthlyCoupleCheckInFactory
    {
        return MonthlyCoupleCheckInFactory::new();
    }
}
