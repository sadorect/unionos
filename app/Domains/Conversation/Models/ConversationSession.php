<?php

namespace App\Domains\Conversation\Models;

use App\Domains\Conversation\Enums\ConversationSessionStatus;
use App\Domains\Couple\Models\Couple;
use App\Models\User;
use Database\Factories\ConversationSessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConversationSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'started_by_user_id',
        'current_turn_user_id',
        'pack_type',
        'pack_version',
        'status',
        'ground_rules_confirmed_at',
        'started_at',
        'paused_at',
        'completed_at',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ConversationSessionStatus::class,
            'ground_rules_confirmed_at' => 'datetime',
            'started_at' => 'datetime',
            'paused_at' => 'datetime',
            'completed_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function startedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'started_by_user_id');
    }

    public function currentTurnUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_turn_user_id');
    }

    public function turns(): HasMany
    {
        return $this->hasMany(ConversationTurn::class, 'session_id');
    }

    protected static function newFactory(): ConversationSessionFactory
    {
        return ConversationSessionFactory::new();
    }
}
