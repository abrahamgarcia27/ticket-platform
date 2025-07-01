<?php /** @noinspection ALL */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\Event;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;



class StripeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function saveTicketsSession(Request $request)
    {
        $request->session()->put('selected_tickets', $request->tickets);
        return response()->json(['success' => true]);
    }

    public function checkout(Request $request)
    {
        $selectedTickets = $request->session()->get('selected_tickets');
        if (!$selectedTickets) {
            return redirect()->back()->with('error', 'No tickets selected');
        }

        $lineItems = [];
        $ticketsData = [];
        foreach ($selectedTickets as $ticketData) {
            $ticket = Ticket::findOrFail($ticketData['ticket_id']);
            $quantity = $ticketData['quantity'];
                
            // Validate quantity and availability
            if ($quantity <= 0 || $quantity > ($ticket->quantity - $ticket->count_orders)) {
                return redirect()->back()->with('error', 'Invalid ticket quantity');
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $ticket->title,
                    ],
                    'unit_amount' => $ticket->price * 100,
                ],
                'quantity' => $quantity,
            ];

            $ticketsData[] = [
                'ticket_id' => $ticket->id,
                'quantity' => $quantity,
                'title' => $ticket->title,
                'price' => $ticket->price
            ];
            
            if ($ticket->fees) {
                foreach ($ticket->fees as $fee) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $fee->name,
                            ],
                            'unit_amount' => $fee->amount * 100,
                        ],
                        'quantity' => $quantity,
                    ];
                }
            }
        }


        $orderData = [
            'name_buyer' => $request->name_buyer,
            'last_name_buyer' => $request->last_name_buyer,
            'email_buyer' => $request->email_buyer,
            'phone_buyer' => $request->phone_buyer,
            'tickets' => $ticketsData,
        ];

        // Store order data in session
        $request->session()->put('order_data', $orderData);

        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.success') . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('event.show', [$ticket->event->id]),
        ]);
    
        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
        $session_id = $request->query('session_id');
        if (!$session_id) {
            return redirect()->route('home')->with('error', 'Invalid session');
        }

        $orderData = $request->session()->get('order_data');
        if (!$orderData) {
            return redirect()->route('home')->with('error', 'Invalid order data');
        }

        $session = \Stripe\Checkout\Session::retrieve($session_id);
        $sessionData = json_encode($session->toArray());
        
        $codes = [];
        $orders_data = [];
        
        // Process each ticket type in the order
        foreach ($orderData['tickets'] as $ticketData) {
            $ticket = Ticket::findOrFail($ticketData['ticket_id']);
            
            // Create orders for each ticket quantity
            for ($i = 0; $i < $ticketData['quantity']; $i++) {
                $orderDetails = [
                    'name_buyer' => $orderData['name_buyer'],
                    'last_name_buyer' => $orderData['last_name_buyer'],
                    'email_buyer' => $orderData['email_buyer'],
                    'phone_buyer' => $orderData['phone_buyer'],
                    'ticket_id' => $ticketData['ticket_id'],
                    'code' => Str::random(5),
                    'stripe_data' => $sessionData
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

                $orders_data[] = [
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
            }
        }

        // Generate PDF with all tickets
        $pdf = PDF::loadView('pages.orders.pdf', ['orders_data' => $orders_data]);

        // Get event details from the first ticket (all tickets are for the same event)
        $event = $orders_data[0]['event_title'];
        Carbon::setLocale('es');
        $startDate = Carbon::parse($ticket->event->date_time_start)->isoFormat('D [de] MMMM, YYYY');
        $startDate = ucwords($startDate);

        $title = $event . ' - ' . $startDate . ' ' . date('(h:s a)', strtotime($ticket->event->date_time_start));
        $clock = $startDate . ' ' . date(' h:s a', strtotime($ticket->event->date_time_start));
        $location = $ticket->event->ubication . ' ' . $ticket->event->street_address . ', ' . 
                    $ticket->event->address_locality . ', ' . $ticket->event->address_region . ' ' . 
                    $ticket->event->postal_code . ', ' . $ticket->event->address_country;

        $emailData = [
            'name' => $orderData['name_buyer'],
            'email' => $orderData['email_buyer'],
            'subject' => $event,
            'title' => $title,
            'clock' => $clock,
            'location' => $location,
            'order_id' => $order->id,
            'order_date' => $fechaRestada,
            'order_quantity' => array_sum(array_column($orderData['tickets'], 'quantity')),
            'user_name' => $ticket->event->user->username,
            'user_email' => $ticket->event->user->email,
            'event_image' => $ticket->event->image,
            'organizer_image' => $ticket->event->user->image,
            'event_location' => $ticket->event->maps_url,
            'tickets' => $orderData['tickets']
        ];
        
        Mail::send('pages.email.email', $emailData, function ($message) use ($emailData, $pdf) {
            $message->from('admin@ticketsplatform.com', $emailData['user_name']);
            $message->to($emailData['email'], $emailData['name']);
            $message->subject($emailData['subject']);
            $message->priority(3);
            $message->attachData($pdf->output(), 'Order.pdf');
        });

        // Clear session data
        $request->session()->forget(['selected_tickets', 'order_data']);

        $codes = implode('-', $codes);
        return redirect()->route('successpage', [$codes]);
    }
}
