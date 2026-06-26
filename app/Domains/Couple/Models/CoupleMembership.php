<?php

namespace App\Domains\Couple\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoupleMembership extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'couple_id',
        'user_id',
        'slot',
        'joined_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'is_active' => 'boolean',
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
}
