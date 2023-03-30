<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Event;
use App\Models\Ticket;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $event = Event::find($id);
        $tickets = Ticket::where('event_id', $id)->get();
        return view('pages.tickets.index', compact('tickets', 'event'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $event = Event::find($id);
        // dd($event);
        return view('pages.tickets.create', compact('event'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        
        $all = $request->except(['_token']);
        $all['event_id'] = $id;
        if ($request->available) {
           $all['available'] = 1;
        }else {
            $all['available'] = 0;
        }
        $ticket = Ticket::create($all);

        return redirect()->route('ticket.index', [$id]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::find($id);
        return view('pages.tickets.edit', compact('ticket'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $all = $request->except(['_token']);
        // dd($request);
        if ($all['type'] == 'free') {
            $all['price'] = null;
        }
        if ($request->available) {
            $all['available'] = 1;
        }else {
            $all['available'] = 0;
        }
        $ticket = Ticket::find($id);
        $ticket->update($all);
        
        return back()->with('succes', 'Ticket succesfully updated');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::find($id);
        $ticket->delete();
        return back()->with('succes', 'Ticket succesfully deleted');

    }
}
