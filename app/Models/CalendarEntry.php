<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEntry extends Model
{
    /** @use HasFactory<\Database\Factories\CalendarEntryFactory> */
    use HasFactory;

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }
}
