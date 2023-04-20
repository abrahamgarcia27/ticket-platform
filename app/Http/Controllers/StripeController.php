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


class StripeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function checkout(Request $request)
    {
        $all = $request->except(['_token']);
        
        $ticket = Ticket::where('id', $all['ticket_id'])->first();
        $all = implode('-', $all);
        
        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $session = \Stripe\Checkout\Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => 'Total',
                        ],
                        'unit_amount'  => $ticket->price * 100,
                    ],
                    'quantity'   => $request->quantity,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('stripe.success', [$all])."?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url'  => route('event.show', [$ticket->event->id]),
        ]);
        
        return redirect()->away($session->url);
    }

    public function success(Request $request, $all)
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
        $session_id = $request->query('session_id');
       
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        $session = $session->toArray();
        $session = json_encode($session);
        
        $array_code = explode('-', $all);
        $all = [
            'name_buyer' => $array_code[0],
            'last_name_buyer' => $array_code[1],
            'email_buyer' => $array_code[2],
            'phone_buyer' => $array_code[3],
            'quantity' => $array_code[4],
            'ticket_id' => $array_code[5]
        ];
        $ticket = Ticket::where('id', $all['ticket_id'])->first();
        // dd($ticket->event->user->email);
        for ($i=0; $i < $all['quantity']; $i++) { 
                $all['code'] = Str::random(5);
                $all['stripe_data'] = $session;
                $order = Order::create($all);
                QrCode::format('png')->size(200)->style('round')->backgroundColor(255, 255, 255)->generate($all['code'], '../public/storage/uploads/'. $all['code'] .'.png');
                $order->update([
                    'svg_qr' => 'uploads/' . $all['code'] . '.png'
                ]);

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

                $data = array(
					'name' => $all['name_buyer'],
					'email' => $all['email_buyer'],
					'subject' => $ticket->event->title,
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
        }
        return redirect('/event' . '/' . $ticket->event->id)->with('succes', 'Successful purchase, you will receive an email with your tickets');

    }
}
