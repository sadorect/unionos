<?php

namespace App\Providers;

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\CheckIn\Policies\MonthlyCoupleCheckInPolicy;
use App\Domains\CheckIn\Policies\WeeklyCheckInPolicy;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleInvite;
use App\Domains\Couple\Policies\CoupleInvitePolicy;
use App\Domains\Couple\Policies\CouplePolicy;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Models\ConversationTurn;
use App\Domains\Conversation\Policies\ConversationSessionPolicy;
use App\Domains\Conversation\Policies\ConversationTurnPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Couple::class, CouplePolicy::class);
        Gate::policy(CoupleInvite::class, CoupleInvitePolicy::class);
        Gate::policy(WeeklyCheckIn::class, WeeklyCheckInPolicy::class);
        Gate::policy(MonthlyCoupleCheckIn::class, MonthlyCoupleCheckInPolicy::class);
        Gate::policy(ConversationSession::class, ConversationSessionPolicy::class);
        Gate::policy(ConversationTurn::class, ConversationTurnPolicy::class);
    }
}
