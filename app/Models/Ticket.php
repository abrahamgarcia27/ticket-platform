<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'tickets';
    protected $fillable = [
        'title',
        'quantity',
        'type',
        'price',
        'fee',
        'date_time_start',
        'date_time_end',
        'event_id',
        'available'
    ];

    public function event(): BelongsTo{
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'ticket_id', 'id');
    }

    public function getSoldTicketsAttribute()
    {
        return $this->orders()->count();
    }

    public function fees()
    {
        return $this->hasMany(FeeTicket::class);
    }
}
