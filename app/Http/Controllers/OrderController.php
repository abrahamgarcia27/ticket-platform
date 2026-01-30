<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Stripe;
use PDF;
use Carbon\Carbon;
use App\Services\ConnectPabblyService;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //Orders by Ticket
    public function index($id)
    {
        $ticket = Ticket::find($id);
        $orders = Order::where('ticket_id', $id)->get();
        
        return view('pages.orders.index', compact('ticket', 'orders'));
        
    }

    public function orderByEvent($id)
    {
        $event = Event::find($id);
        return view('pages.orders.orders-by-event', compact('event'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $orderData = $request->session()->get('selected_tickets');
        if (!$orderData) {
            return redirect()->back()->with('error', 'Invalid order data');
        }

        foreach ($orderData as &$ticketData) {
            $ticket = Ticket::where('id', $ticketData['ticket_id'])->first();
            $ticketData['title'] = $ticket->title; // Agregar el título al array original
            
            $ordersTicket = Order::where('ticket_id', $ticket->id)->get();
            if ($ordersTicket->where('email_buyer', $request['email_buyer'])->count() >= 10) {
                return back()->with('error', 'You have exceeded the maximum number of tickets per person.');
            }
            if ($ordersTicket->count() >= $ticket->quantity) {
                return back()->with('error', 'There are no more tickets available.');
            }
            if ($ticketData['quantity'] > 10) {
                return back()->with('error', 'You can not buy more than 10 tickets.');
            }
        }
        unset($ticketData);
        
        $codes = [];
        $orders_data = [];

        foreach ($orderData as $ticketData) {

            $ticket = Ticket::findOrFail($ticketData['ticket_id']);
            
            // Create orders for each ticket quantity
            for ($i = 0; $i < $ticketData['quantity']; $i++) {
                $orderDetails = [
                    'name_buyer' => $request['name_buyer'],
                    'last_name_buyer' => $request['last_name_buyer'],
                    'email_buyer' => $request['email_buyer'],
                    'phone_buyer' => $request['phone_buyer'],
                    'ticket_id' => $ticketData['ticket_id'],
                    'code' => Str::random(5),
                ];

                $order = Order::create($orderDetails);
                
                // Generate QR code
                QrCode::format('png')
                    ->size(200)
                    ->style('round')
                    ->backgroundColor(255, 255, 255)
                    ->generate($orderDetails['code'], '../public/storage/uploads/'. $orderDetails['code'] .'.png');
                
                $order->update([
                    'svg_qr' => 'uploads/' . $orderDetails['code'] . '.png'
                ]);
                
                $codes[] = $order->code;
                
                $event = Event::where('id', $order->ticket->event_id)->first();
                $created_at = Carbon::parse($order->created_at);
                $fechaRestada = $created_at->subHours(6);

                $order_data = [
                    'event_title'     => $event->title,
                    'event_ubication' => $event->ubication,
                    'event_datetime'  => $event->date_time_start,
                    'order'           => $order->id,
                    'type_ticket'     => $order->ticket->type,
                    'name_ticket'     => $order->ticket->title,
                    'name_buyer'      => $order->name_buyer . ' ' . $order->last_name_buyer,
                    'order_date'      => $fechaRestada,
                    'qr'              => $order->svg_qr,
                    'website'         => $event->user->web_url
                ];
                $orders_data[] = $order_data;
            }
            $order_data['quantity'] = $ticketData['quantity'];
            $order_data['email_buyer'] = $order->email_buyer;
            $order_data['phone_buyer'] = $order->phone_buyer;
            $order_data['event_address'] = $event->street_address;
            $order_data['event_city'] = $event->address_locality;
            $order_data['event_state'] = $event->address_region;
            $order_data['event_postal_code'] = $event->postal_code;
            $order_data['event_country'] = $event->address_country;
            $order_data['event_maps_url'] = $event->maps_url;
            $order_data['event_date_time_end'] = $event->date_time_end;
            $order_data['event_summary'] = $event->summary;
            $order_data['event_about'] = $event->about;
            $order_data['event_meta_title'] = $event->meta_title;
            $order_data['event_meta_description'] = $event->meta_description;
            $order_data['event_link_external'] = $event->link_external_Sales;

            // Send order data to Pabbly
            $connectPabblyService = new ConnectPabblyService();
            $connectPabblyService->sendOrderData($order_data);
        }

        $pdf = PDF::loadView('pages.orders.pdf', ['orders_data' => $orders_data]);

        $event = $orders_data[0]['event_title'];
        $title = $ticket->event->title . ' - ' . date('j F, Y (h:s a)', strtotime($ticket->event->date_time_start));
        $clock = date('j F, Y h:s a', strtotime($ticket->event->date_time_start));
        $location = $ticket->event->ubication . ' ' . $ticket->event->street_address . ', ' . $ticket->event->address_locality . ', ' . $ticket->event->address_region . ' ' . $ticket->event->postal_code . ', ' . $ticket->event->address_country;
        
        $emailData = [
            'name' => $request['name_buyer'],
            'email' => $request['email_buyer'],
            'subject' => $event,
            'title' => $title,
            'clock' => $clock,
            'location' => $location,
            'order_id' => $order->id,
            'order_date' => $fechaRestada,
            'order_quantity' => array_sum(array_column($orderData, 'quantity')),
            'user_name' => $ticket->event->user->username,
            'user_email' => $ticket->event->user->email,
            'event_image' => $ticket->event->image,
            'organizer_image' => $ticket->event->user->image,
            'event_location' => $ticket->event->maps_url,
            'tickets' => $orderData
        ];

        Mail::send('pages.email.email', $emailData, function ($message) use ($emailData, $pdf) {
            $message->from('admin@ticketsplatform.com', $emailData['user_name']);
            $message->to($emailData['email'], $emailData['name']);
            $message->subject($emailData['subject']);
            $message->priority(3);
            $message->attachData($pdf->output(), 'Order.pdf');
        });

        // Clear session data
        $request->session()->forget(['selected_tickets', 'selected_tickets']);

        $codes = implode('-', $codes);
        return redirect()->route('successpage', [$codes]);
       
    }

    public function successpage($codes)
    {
        $codes = explode('-', $codes);
        $order = Order::where('code', $codes[0])->first();
        $event = Event::where('id', $order->ticket->event_id)->first(); 
        $location = $event->ubication . ' ' . $event->street_address . ', ' . $event->address_locality . ', ' . $event->address_region . ' ' . $event->postal_code . ', ' . $event->address_country;

        // dd($event);
        return view('pages.orders.successpage', compact('event', 'order', 'codes', 'location'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function test() 
    {
        $code = 'PGd3t';
        $order = Order::where('code', $code)->first();
        $event = Event::where('id', $order->ticket->event_id)->first(); 
        $ticket = Ticket::where('id', '2')->first();
        $title = $ticket->event->title . ' - ' . date('j F, Y (h:s a)', strtotime($ticket->event->date_time_start));
        $clock = date('j F, Y h:s a', strtotime($ticket->event->date_time_start)) . ' to ' . date('j F, Y h:s a', strtotime($ticket->event->date_time_end));
        $location = $ticket->event->ubication . ' ' . $ticket->event->street_address . ', ' . $ticket->event->address_locality . ', ' . $ticket->event->address_region . ' ' . $ticket->event->postal_code . ', ' . $ticket->event->address_country;
        $order_date = date('j F, Y', strtotime($order->created_at));

        $data = array(
            'name' => 'Arturo',
            'email' => 'arturoalvavi989@gmail.com',
            'subject' => $ticket->event->title,
            'title' => $title,
            'clock' => $clock,
            'location' => $location,
            'order_id' => $order->id,
            'order_date' => $order_date,
            'order_quantity' => '1',
            'ticket_price' => $ticket->price,
            'ticket_title' => $ticket->title,
            'ticket_type' => $ticket->type,
            'user_name' => $ticket->event->user->username,
            'user_email' => $ticket->event->user->email,
            'event_image' => $ticket->event->image,
            'organizer_image' => $ticket->event->user->image,
            'event_location' => $ticket->event->maps_url,
            'code' => $order->code
        );
        // Mail::send('pages.email.email', $data, function ($message) use ($data) {
        //     $message->from('admin@ticketsplatform.com', $data['user_name']);
        //     $message->to($data['email'], $data['name']);
        //     $message->subject($data['subject']);
        //     $message->priority(3);
    
        // });
        return view('pages.email.email', $data); 
        // $pdf = PDF::loadView('pages.orders.pdf', [
        //     'event_title'     => $event->title,
        //     'event_ubication' => $event->ubication,
        //     'event_datetime'  => $event->date_time_start,
        //     'order'           => $order->id,
        //     'type_ticket'     => $order->ticket->type,
        //     'name_ticket'     => $order->ticket->title,
        //     'name_buyer'      => $order->name_buyer . ' ' . $order->last_name_buyer,
        //     'order_date'      => $order->created_at,
        //     'qr'              => $order->svg_qr,
        //     'website'         => $event->user->web_url
        // ]);
        // $data = [
        //     'event_title'     => $event->title,
        //     'event_ubication' => $event->ubication,
        //     'event_datetime'  => $event->date_time_start,
        //     'order'           => $order->id,
        //     'type_ticket'     => $order->ticket->type,
        //     'name_ticket'     => $order->ticket->title,
        //     'name_buyer'      => $order->name_buyer . ' ' . $order->last_name_buyer,
        //     'order_date'      => $order->created_at,
        //     'qr'              => $order->svg_qr,
        //     'website'         => $event->user->web_url
        // ];
        
        // return view('pages.orders.pdf', $data);
        // return $pdf->download('sample.pdf');

    }
    public function pdf($code) 
    {   
        
        $order = Order::where('code', $code)->first();
        $event = Event::where('id', $order->ticket->event_id)->first(); 
        $pdf = PDF::loadView('pages.orders.pdf', [
            'event_title'     => $event->title,
            'event_ubication' => $event->ubication,
            'event_datetime'  => $event->date_time_start,
            'order'           => $order->id,
            'type_ticket'     => $order->ticket->type,
            'name_ticket'     => $order->ticket->title,
            'name_buyer'      => $order->name_buyer . ' ' . $order->last_name_buyer,
            'order_date'      => $order->created_at,
            'qr'              => $order->svg_qr,
            'website'         => $event->user->web_url
        ]);
        return $pdf->download('sample.pdf');
        
    }

    public function resendOrderEmail($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        $ticket = $order->ticket;
        $event = Event::where('id', $ticket->event_id)->first();

        $created_at = Carbon::parse($order->created_at);
        $fechaRestada = $created_at->subHours(6);

        $orders_data = [
            [
                'event_title'     => $event->title,
                'event_ubication' => $event->ubication,
                'event_datetime'  => $event->date_time_start,
                'order'           => $order->id,
                'type_ticket'     => $order->ticket->type,
                'name_ticket'     => $order->ticket->title,
                'name_buyer'      => $order->name_buyer . ' ' . $order->last_name_buyer,
                'order_date'      => $fechaRestada,
                'qr'              => $order->svg_qr,
                'website'         => $event->user->web_url
            ]
        ];

        $pdf = PDF::loadView('pages.orders.pdf', ['orders_data' => $orders_data]);

        $title = $ticket->event->title . ' - ' . date('j F, Y (h:s a)', strtotime($ticket->event->date_time_start));
        $clock = date('j F, Y h:s a', strtotime($ticket->event->date_time_start)) . ' to ' . date('j F, Y h:s a', strtotime($ticket->event->date_time_end));
        $location = $ticket->event->ubication . ' ' . $ticket->event->street_address . ', ' . $ticket->event->address_locality . ', ' . $ticket->event->address_region . ' ' . $ticket->event->postal_code . ', ' . $ticket->event->address_country;
        $order_date = date('j F, Y', strtotime($fechaRestada));

        $data = array(
            'name' => $order->name_buyer,
            'email' => 'arturoalvavi98@gmail.com',
            'subject' => $ticket->event->title,
            'title' => $title,
            'clock' => $clock,
            'location' => $location,
            'order_id' => $order->id,
            'order_date' => $order_date,
            'order_quantity' => 1,
            'ticket_price' => $ticket->price,
            'ticket_title' => $ticket->title,
            'ticket_type' => $ticket->type,
            'user_name' => $ticket->event->user->username,
            'user_email' => $ticket->event->user->email,
            'event_image' => $ticket->event->image,
            'organizer_image' => $ticket->event->user->image,
            'event_location' => $ticket->event->maps_url,
            'code' => $order->code
        );

        Mail::send('pages.email.email', $data, function ($message) use ($data, $pdf) {
            $message->from('admin@ticketsplatform.com', $data['user_name']);
            $message->to($data['email'], $data['name']);
            $message->subject($data['subject']);
            $message->priority(3);
            $message->attachData($pdf->output(), 'Order.pdf');
        });

        return back()->with('success', 'Email sent successfully.');
    }

    /**
     * Genera y descarga el PDF de órdenes por evento (usado por enlace firmado en reporte diario).
     */
    public function downloadEventOrdersPdf($id)
    {
        $event = Event::with(['orders.ticket'])->findOrFail($id);
        $pdf = PDF::loadView('pages.orders.orders-by-event-pdf', compact('event'));
        $filename = 'orders-' . \Illuminate\Support\Str::slug($event->title) . '-' . $event->id . '.pdf';
        return $pdf->download($filename);
    }
}
