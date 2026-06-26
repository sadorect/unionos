<?php

namespace App\Domains\CheckIn\Models;

use App\Domains\Couple\Models\Couple;
use App\Models\User;
use Database\Factories\WeeklyCheckInFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyCheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'couple_id',
        'week_start_date',
        'emotional_availability',
        'communication_effort',
        'stress_level',
        'appreciation_text',
        'struggle_text',
        'intention_text',
    ];

    protected function casts(): array
    {
        return [
            'week_start_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    protected static function newFactory(): WeeklyCheckInFactory
    {
        return WeeklyCheckInFactory::new();
    }
}
