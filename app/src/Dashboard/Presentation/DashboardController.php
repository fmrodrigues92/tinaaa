<?php

namespace App\Src\Dashboard\Presentation;

use App\Http\Controllers\Controller;
use App\Src\Dashboard\Application\DashboardOverviewService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request, DashboardOverviewService $overview): Response
    {
        return Inertia::render('dashboard', $overview->forUser($request->user()));
    }
}
