<?php

namespace App\Domains\Notification\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationDispatchLog extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'kind',
        'channel',
        'status',
        'reason',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'delivered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
