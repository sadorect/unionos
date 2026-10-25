<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Models\Couple;
use App\Domains\Notification\Models\NotificationDispatchLog;
use App\Domains\System\Services\OperationalMetricsService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function __construct(
        private readonly OperationalMetricsService $operationalMetrics,
    ) {
    }

    public function dashboard(): Response
    {
        $stats = [
            'total_users' => User::count(),
            'active_couples' => Couple::where('status', 'active')->count(),
            'total_couples' => Couple::count(),
            'completed_conversations' => ConversationSession::where('status', 'completed')->count(),
            'notifications_sent' => NotificationDispatchLog::where('status', 'sent')->count(),
        ];

        $users = User::withTrashed()
            ->with([
                'coupleMemberships' => fn ($q) => $q->with('couple:id,status'),
            ])
            ->latest()
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'is_admin' => $u->is_admin,
                'deleted_at' => $u->deleted_at?->toDateString(),
                'created_at' => $u->created_at->toDateString(),
                'couple_status' => $u->coupleMemberships->first()?->couple?->status ?? 'none',
            ]);

        $recentLogs = NotificationDispatchLog::with('user:id,name,email')
            ->latest()
            ->take(60)
            ->get()
            ->map(fn (NotificationDispatchLog $log) => [
                'id' => $log->id,
                'user_name' => $log->user?->name ?? '—',
                'user_email' => $log->user?->email ?? '—',
                'kind' => $log->kind,
                'channel' => $log->channel,
                'status' => $log->status,
                'reason' => $log->reason,
                'created_at' => $log->created_at->format('Y-m-d H:i'),
            ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'users' => $users,
            'recentLogs' => $recentLogs,
            'health' => $this->operationalMetrics->health(),
            'metrics' => $this->operationalMetrics->metrics(),
        ]);
    }

    public function toggleAdmin(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_if($user->is($request->user()), 403, 'Cannot change your own admin status.');

        $user->update(['is_admin' => ! $user->is_admin]);

        return back()->with('success', $user->is_admin
            ? "{$user->name} is now an admin."
            : "{$user->name} is no longer an admin.");
    }
}
