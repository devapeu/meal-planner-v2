<?php

namespace App\Http\Controllers;

use App\Models\CalendarEntry;
use App\Models\Recipe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $startDate = $request->query('startDate', now()->toDateString());

        $calendar = CalendarEntry::where('user_id', $request->user()->id)
            ->forWeek($startDate)
            ->get(['id', 'meal', 'start_date', 'end_date', 'recipe_id'])
            ->map(fn($e) => [
                'id'         => $e->id,
                'meal'       => $e->meal,
                'start_date' => $e->start_date->toDateString(),
                'end_date'   => $e->end_date->toDateString(),
                'duration'   => $e->duration,
                'recipe_id'  => $e->recipe_id,
            ]);

        $recipes = Recipe::where('user_id', $request->user()->id)
            ->get(['id', 'name', 'content']);

        return Inertia::render('Home', [
            'calendar'  => $calendar,
            'startDate' => $startDate,
            'recipes'   => $recipes,
        ]);
    }
}
