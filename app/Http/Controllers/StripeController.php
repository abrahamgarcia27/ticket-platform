<?php /** @noinspection ALL */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

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
                        'unit_amount'  => 500,
                    ],
                    'quantity'   => $request->quantity,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('stripe.success', [$all]),
            'cancel_url'  => route('event.show', [$ticket->event->id]),
        ]);
        
        return redirect()->away($session->url);
    }

    public function success($all)
    {
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
                $order = Order::create($all);
                QrCode::format('png')->size(100)->generate($all['code'], '../public/storage/uploads/'. $all['code'] .'.png');
                $order->update([
                    'svg_qr' => 'uploads/' . $all['code'] . '.png'
                ]);
                
                $data = array(
					'name' => $all['name_buyer'],
					'email' => $all['email_buyer'],
					'subject' => $ticket->event->title,
                    'user_name' => $ticket->event->user->username,
                    'user_email' => $ticket->event->user->email
				);
                Mail::send('pages.email.email', $data, function ($message) use ($data) {
					$message->from('admin@marketingnature.com', $data['user_name']);
					$message->to($data['email'], $data['name']);
					$message->subject($data['subject']);
					$message->priority(3);
				});
        }
        return redirect('/event' . '/' . $ticket->event->id)->with('succes', 'Successful purchase, you will receive an email with your tickets');

    }
}