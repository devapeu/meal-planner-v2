<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEntry extends Model
{
    /** @use HasFactory<\Database\Factories\CalendarEntryFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipes_id',
        'meal',
        'start_date',
        'end_date',
    ];

    protected $appends = [
        'duration',
    ];

    protected function duration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->start_date->diffInDays($this->end_date) + 1,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
