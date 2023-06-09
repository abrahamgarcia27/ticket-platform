<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function getOrganizers()
    {   
        $organizers = User::with('events')->get();
        
        return $organizers; 
    }
    public function getEvents()
    {   
        $events = Event::withCount('orders')->with('totalTickets')->get();
        $events = $events->map(function ($event) {
            $event['earned'] = $event->totalPrice;
            return $event;
        })->makeHidden('orders');
        
        return $events; 
    }
    public function getEvent($id)
    {   
        $event = Event::with('orders')->where('id', $id)->first();
        
        return $event; 
    }
    public function getOrders()
    {   
        $orders = Order::all();
        
        return $orders; 
    }

    public function assistOrder($code)
    {   
        $date = Carbon::now();
        $formattedDate = $date->format('F j, Y');
        $order = Order::where('code', $code)->first();
        if ($order == null) {
            $response = [
                'message' => 'The code ' . $code . ' dont exist'
            ];
        }
        if ($order->assist == null) {
            $order->update([
                'assist' => true
            ]);
            $response = [
                'message' => 'Confirmed attendance for ' . $order->name_buyer . ' at the ' . $order->ticket->event->title . ' event ' . $formattedDate
            ];
            return $response; 
        } else {
            $response = [
                'message' => 'This code has already been registered'
            ];
            return $response; 
        }
    }
}
