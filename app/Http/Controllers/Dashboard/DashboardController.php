<?php

namespace App\Http\Controllers\Dashboard;

use App\Domains\Dashboard\Services\DashboardService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboards,
    ) {
    }

    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard.couple');
    }

    public function personal(Request $request): Response
    {
        $this->authorize('view', $request->user());

        return Inertia::render('Dashboard/Personal', $this->dashboards->personal($request->user()));
    }

    public function personalForUser(Request $request, User $user): Response
    {
        if (! $request->user()->is($user)) {
            return Inertia::render('State/PrivateContent');
        }

        return Inertia::render('Dashboard/Personal', $this->dashboards->personal($user));
    }

    public function couple(Request $request): Response
    {
        return Inertia::render('Dashboard/Couple', $this->dashboards->couple($request->user()));
    }
}
