<?php

namespace App\Http\Controllers;

use App\Models\CalendarEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalendarEntryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'meal'       => ['required', 'string', 'max:255'],
            'startDate'  => ['required', 'date'],
            'endDate'    => ['required', 'date', 'gte:startDate'],
            'recipeId'   => ['nullable', 'integer', 'exists:recipes,id'],
        ]);

        CalendarEntry::create([
            'user_id'    => $request->user()->id,
            'meal'       => $data['meal'],
            'start_date' => $data['startDate'],
            'end_date'   => $data['endDate'],
            'recipe_id'  => $data['recipeId'] ?? null,
        ]);

        return redirect()->route('home', ['startDate' => $data['startDate']]);
    }

    public function destroy(Request $request, CalendarEntry $calendarEntry): RedirectResponse
    {
        abort_unless($calendarEntry->user_id === $request->user()->id, 403);

        $startDate = $request->query('startDate', $calendarEntry->start_date->toDateString());
        $calendarEntry->delete();

        return redirect()->route('home', ['startDate' => $startDate]);
    }
}
