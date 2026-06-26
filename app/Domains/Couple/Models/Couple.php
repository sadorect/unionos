<?php

namespace App\Domains\Couple\Models;

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Enums\CoupleStatus;
use Database\Factories\CoupleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Couple extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => CoupleStatus::class,
        ];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(CoupleMembership::class);
    }

    public function invites(): HasMany
    {
        return $this->hasMany(CoupleInvite::class);
    }

    public function weeklyCheckIns(): HasMany
    {
        return $this->hasMany(WeeklyCheckIn::class);
    }

    public function monthlyCoupleCheckIns(): HasMany
    {
        return $this->hasMany(MonthlyCoupleCheckIn::class);
    }

    public function conversationSessions(): HasMany
    {
        return $this->hasMany(ConversationSession::class);
    }

    protected static function newFactory(): CoupleFactory
    {
        return CoupleFactory::new();
    }
}
