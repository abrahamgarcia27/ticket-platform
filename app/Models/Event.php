<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $fillable = [
        'title',
        'ubication',
        'street_address',
        'address_locality',
        'postal_code',
        'address_region',
        'address_country',
        'about',
        'maps_url',
        'date_time_start',
        'date_time_end',
        'coverimage',
        'image',
        'summary',
        'created_by',
        'meta_title',
        'meta_description',
        'link_external_sales',
        'google_event_id',
        'report_sent_at',
    ];

    protected $casts = [
        'report_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'event_id', 'id');
    }

    public function totalTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'event_id', 'id')
            ->selectRaw('event_id, sum(quantity) as total_quantity')
            ->groupBy('event_id');
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Ticket::class,
            'event_id', // Foreign key on the environments table...
            'ticket_id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->orders->sum(function ($order) {
            return $order->ticket->price;
        });
    }
   

}
