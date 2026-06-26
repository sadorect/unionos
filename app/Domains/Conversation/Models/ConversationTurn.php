<?php

namespace App\Domains\Conversation\Models;

use App\Models\User;
use Database\Factories\ConversationTurnFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationTurn extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'user_id',
        'prompt_key',
        'response_text',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ConversationSession::class, 'session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): ConversationTurnFactory
    {
        return ConversationTurnFactory::new();
    }
}
