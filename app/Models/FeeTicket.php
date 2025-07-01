<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeTicket extends Model
{
    use HasFactory;

    protected $table = 'fees_tickets';

    protected $fillable = [
        'name',
        'amount',
        'ticket_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
