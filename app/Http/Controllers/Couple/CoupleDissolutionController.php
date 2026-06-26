<?php

namespace App\Http\Controllers\Couple;

use App\Actions\Couple\DissolveCouple;
use App\Domains\Couple\Services\CoupleStateService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CoupleDissolutionController extends Controller
{
    public function __construct(
        private readonly CoupleStateService $state,
        private readonly DissolveCouple $dissolveCouple,
    ) {
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $couple = $this->state->current($request->user());

        if ($couple === null) {
            return redirect()->route('couple.create');
        }

        $this->authorize('dissolve', $couple);
        $this->dissolveCouple->handle($couple);

        return redirect()->route('couple.dissolved')
            ->with('success', 'This couple space has been dissolved.');
    }
}
