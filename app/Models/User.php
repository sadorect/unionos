<?php

namespace App\Models;

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Models\ConversationTurn;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Domains\Notification\Models\NotificationDispatchLog;
use App\Domains\Notification\Models\NotificationPreference;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'bool',
        ];
    }

    public function coupleMemberships(): HasMany
    {
        return $this->hasMany(CoupleMembership::class);
    }

    public function couples(): BelongsToMany
    {
        return $this->belongsToMany(Couple::class, 'couple_memberships')
            ->withPivot(['joined_at', 'is_active', 'slot']);
    }

    public function weeklyCheckIns(): HasMany
    {
        return $this->hasMany(WeeklyCheckIn::class);
    }

    public function monthlyCoupleCheckIns(): HasMany
    {
        return $this->hasMany(MonthlyCoupleCheckIn::class);
    }

    public function conversationSessionsStarted(): HasMany
    {
        return $this->hasMany(ConversationSession::class, 'started_by_user_id');
    }

    public function conversationTurns(): HasMany
    {
        return $this->hasMany(ConversationTurn::class);
    }

    public function notificationPreference(): HasOne
    {
        return $this->hasOne(NotificationPreference::class);
    }

    public function notificationDispatchLogs(): HasMany
    {
        return $this->hasMany(NotificationDispatchLog::class);
    }
}
