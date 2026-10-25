<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class GuideController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Guide');
    }
}
