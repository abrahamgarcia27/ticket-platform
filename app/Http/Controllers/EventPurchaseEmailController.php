<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\PurchaseEmailService;
use Illuminate\Http\Request;

/**
 * "Reenviar emails de compra": resend the purchase email to selected orders of an event.
 */
class EventPurchaseEmailController extends Controller
{
    // Some events have thousands of orders: list them paginated and keep each send small
    // (synchronous SMTP + PDF per buyer, and PHP max_input_vars).
    private const ORDERS_PER_PAGE = 100;
    private const MAX_ORDERS_PER_REQUEST = 100;

    public function show(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $search = trim((string) $request->query('search', ''));

        $query = $event->orders()->with('ticket')->orderBy('orders.id');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $like = '%' . $search . '%';
                if (ctype_digit(ltrim($search, '#'))) {
                    $q->orWhere('orders.id', (int) ltrim($search, '#'));
                }
                $q->orWhere('orders.code', 'like', $like)
                    ->orWhere('orders.email_buyer', 'like', $like)
                    ->orWhere('orders.name_buyer', 'like', $like)
                    ->orWhere('orders.last_name_buyer', 'like', $like);
            });
        }

        $orders = $query->paginate(self::ORDERS_PER_PAGE, ['orders.*'])->withQueryString();

        if ($orders->currentPage() > 1 && $orders->currentPage() > $orders->lastPage()) {
            return redirect()->route('event.resend-emails', array_filter([
                'id' => $event->id,
                'search' => $search,
                'page' => $orders->lastPage() > 1 ? $orders->lastPage() : null,
            ]));
        }

        return view('pages.event.resend-emails', compact('event', 'orders', 'search'));
    }

    public function send(Request $request, $id, PurchaseEmailService $purchaseEmail)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'order_ids' => ['required', 'array', 'min:1', 'max:' . self::MAX_ORDERS_PER_REQUEST],
            'order_ids.*' => ['required', 'integer', 'distinct'],
        ], [
            'order_ids.required' => 'Selecciona al menos una orden.',
            'order_ids.min' => 'Selecciona al menos una orden.',
            'order_ids.max' => 'Puedes reenviar como máximo ' . self::MAX_ORDERS_PER_REQUEST . ' órdenes a la vez.',
        ]);

        $orderIds = array_map('intval', $validated['order_ids']);

        // Only orders that belong to this event (orders -> tickets -> event).
        $orders = $event->orders()->with('ticket')->whereIn('orders.id', $orderIds)->get();
        if ($orders->count() !== count($orderIds)) {
            return redirect()->back(302, [], route('event.resend-emails', $event->id))
                ->with('error', 'Algunas órdenes seleccionadas no pertenecen a este evento. No se envió ningún correo.');
        }

        @set_time_limit(300);
        $result = $purchaseEmail->resendForOrders($event, $orders);

        $message = "Correos reenviados: {$result['sent']} ({$result['sent_orders']} órdenes). Fallidos: {$result['failed']}.";
        $redirect = redirect()->back(302, [], route('event.resend-emails', $event->id));

        if ($result['failed'] > 0) {
            return $redirect->with('error', $message . ' No se pudo enviar a: ' . implode(', ', $result['failed_emails']));
        }

        return $redirect->with('succes', $message);
    }
}
