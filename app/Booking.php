<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'start',
        'end',
        'notes',
    ];

    protected $dates = [
        'start',
        'end',
    ];

    protected $appends = [
        'formatted_date',
    ];

    public function getFormattedDateAttribute(): string
    {
        $startFormatted = $this->start->format('l d F Y H:i');
        if ($this->start->isSameDay($this->end)) {
            return $startFormatted . ' - ' . $this->end->format('H:i');
        }
        return $startFormatted . ' - ' . $this->end->format('l d F Y H:i');
    }
}
