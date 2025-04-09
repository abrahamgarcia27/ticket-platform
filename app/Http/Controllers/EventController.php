<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
Use \Carbon\Carbon;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event as GoogleEvent;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $yesterday = Carbon::yesterday()->toDateString();
        if(request()->has('past-events')) {
            $events = Event::where('date_time_start', '<=', $yesterday)->get();
        } else {
            $events = Event::where('date_time_start', '>', $yesterday)->get();
        }
        return view('pages.event.index', compact('events'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.event.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $request->validate([
            'date_time_start' => 'required|date',
            'date_time_end' => 'required|date|after:date_time_start'
        ]);

        $all = $request->except(['_token', 'image']);
        $all['created_by'] = Auth::id();
        
        if (!$request->has('external_sales')) {
            $all['link_external_sales'] = null;
        }
        $event = Event::create($all);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('uploads','public');
            Event::find($event->id)->update([
                'image' => $image         
            ]);
        }
        if ($request->hasFile('coverimage')) {
            $image = $request->file('coverimage')->store('uploads','public');
            Event::find($event->id)->update([
                'coverimage' => $image         
            ]);
        }
        $id = $event->id; 

        if (env('APP_ENV') == 'production') {
            $this->createGoogleEvent($event);
        }

        if ($request->has('external_sales')) {
            return redirect()->route('event.index')->with('succes', 'Event succesfully updated');

        }

        return redirect()->route('ticket.create', [$id]);

    }

    public function clonar($id)
    {
        // Buscar el evento original
        $originalEvent = Event::findOrFail($id);

        // Crear una copia del evento
        $clonedEvent = $originalEvent->replicate();
        $clonedEvent->created_by = Auth::id();
        $clonedEvent->date_time_start = Carbon::parse($originalEvent->date_time_start)->addWeek()->format('Y-m-d H:i');
        $clonedEvent->date_time_end = Carbon::parse($originalEvent->date_time_end)->addWeek()->format('Y-m-d H:i');
        $clonedEvent->save();

        // Clonar los tickets del evento original
        $originalTickets = Ticket::where('event_id', $originalEvent->id)->get();
        foreach ($originalTickets as $originalTicket) {
            $clonedTicket = $originalTicket->replicate();
            $clonedTicket->event_id = $clonedEvent->id; // Asignar el nuevo evento clonado
            $clonedTicket->date_time_start = Carbon::parse($originalTicket->date_time_start)->addWeek()->format('Y-m-d H:i');
            $clonedTicket->date_time_end = Carbon::parse($originalTicket->date_time_end)->addWeek()->format('Y-m-d H:i');
            $clonedTicket->save();
        }
        if (env('APP_ENV') == 'production') {
            $this->createGoogleEvent($clonedEvent);
        }
        // Redirigir al usuario a la página de edición del nuevo evento clonado
        return redirect()->route('event.index')->with('succes', 'Event succesfully duplicated');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $data['event'] = Event::find($id);
        $data['ticket'] = Ticket::where('event_id', $id)->where('available', '1')->first();
        if ($data['ticket'] != null) {
            $data['count_orders'] = Order::where('ticket_id', $data['ticket']->id)->count();
        }else {
            $data['count_orders'] = 0;

        }

        $data['tickets'] = Ticket::where('event_id', $id)->where('available', '1')->get();
        
        $data['location'] = $data['event']->ubication . ' ' . $data['event']->street_address . ', ' . $data['event']->address_locality . ', ' . $data['event']->address_region . ' ' . $data['event']->postal_code . ', ' . $data['event']->address_country;
        $data['today'] = Carbon::now()->toDateTimeString();
        //dd($data);
        return view('pages.event.show', $data);

    }

    public function events()
    {
        $today = Carbon::today()->toDateString();
        $events = Event::where('date_time_start', '>', $today)->orderBy('date_time_start', 'asc')->get();
        $header = env('TITLE_HEADER');
        return view('pages.event.events', compact('events', 'header'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::find($id);
        // dd($event->tickets);
        return view('pages.event.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $all = $request->except(['_token', 'image']);
        $event = Event::find($id);
        if (!$request->has('external_sales')) {
            $all['link_external_sales'] = null;
        }
        $event->update($all);
        if ($request->hasFile('image')) {
            Storage::delete('public/'.$event->image);
            $image = $request->file('image')->store('uploads','public');
            Event::find($event->id)->update([
                'image' => $image         
            ]);
        }
        if ($request->hasFile('coverimage')) {
            Storage::delete('public/'.$event->image);
            $image = $request->file('coverimage')->store('uploads','public');
            Event::find($event->id)->update([
                'coverimage' => $image         
            ]);
        }
        if (env('APP_ENV') == 'production' && $event->google_event_id !== null) {
            $this->updateGoogleEvent($event);
        }

        return back()->with('succes', 'Event succesfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);
        if (env('APP_ENV') == 'production') {
            $this->deleteGooogleEvent($event);
        }
        $event->delete();
        return back()->with('succes', 'Event succesfully deleted');
    }

    public function createGoogleEvent($event)
    {
        $client = new Client();
        $client->setAuthConfig(config('google.service_account_key_path'));
        $client->addScope(\Google_Service_Calendar::CALENDAR);

        $service = new Calendar($client);

        $startDateTime = Carbon::parse($event->date_time_start)->format('Y-m-d\TH:i:sP');
        $endDateTime = Carbon::parse($event->date_time_end)->format('Y-m-d\TH:i:sP');

        $googleEvent = new GoogleEvent([
            'summary' => $event->title,
            'location' => $event->ubication,
            'description' => $event->summary,
            'start' => [
                'dateTime' => $startDateTime,
                'timeZone' => env('APP_TIMEZONE'),
            ],
            'end' => [
                'dateTime' => $endDateTime,
                'timeZone' => env('APP_TIMEZONE'),
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 24 * 60],
                    ['method' => 'popup', 'minutes' => 10],
                ],
            ],
        ]);

        $calendarId = env('GOOGLE_CALENDAR_ID'); 

        $createdEvent = $service->events->insert($calendarId, $googleEvent);
        $googleEventId = $createdEvent->getId();

        $event->google_event_id = $googleEventId;
        $event->save();

        return response()->json(['message' => 'Evento creado', 'event' => $createdEvent]);
    }

    public function updateGoogleEvent($event)
    {
        $client = new Client();
        $client->setAuthConfig(config('google.service_account_key_path'));
        $client->addScope(\Google_Service_Calendar::CALENDAR);

        $service = new Calendar($client);

        $calendarId = env('GOOGLE_CALENDAR_ID');
        $googleEvent = $service->events->get($calendarId, $event->google_event_id);

        $googleEvent->setSummary($event->title);
        $googleEvent->setDescription($event->summary);
        $googleEvent->setLocation($event->ubication);

        $startDateTime = Carbon::parse($event->date_time_start)->format('Y-m-d\TH:i:sP');
        $endDateTime = Carbon::parse($event->date_time_end)->format('Y-m-d\TH:i:sP');

        $googleEvent->start = new \Google_Service_Calendar_EventDateTime([
            'dateTime' => $startDateTime,
            'timeZone' => env('APP_TIMEZONE'),
        ]);
        $googleEvent->end = new \Google_Service_Calendar_EventDateTime([
            'dateTime' => $endDateTime,
            'timeZone' => env('APP_TIMEZONE'),
        ]);

        $updatedEvent = $service->events->update($calendarId, $googleEvent->getId(), $googleEvent);

        return response()->json(['message' => 'Evento actualizado', 'event' => $updatedEvent]);
    }

    public function deleteGooogleEvent($event)
    {
        $client = new Client();
        $client->setAuthConfig(config('google.service_account_key_path'));
        $client->addScope(\Google_Service_Calendar::CALENDAR);

        $service = new Calendar($client);
        $calendarId = env('GOOGLE_CALENDAR_ID');

        $service->events->delete($calendarId, $event->google_event_id);
    }
    
}
