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
        // Only a plain string is accepted (?search[]=x is ignored instead of failing).
        $rawSearch = $request->query('search');
        $search = is_string($rawSearch) ? mb_substr(trim($rawSearch), 0, 100) : '';

        $query = $event->orders()->with('ticket')->orderBy('orders.id');
        if ($search !== '') {
            $words = preg_split('/\s+/u', $search, -1, PREG_SPLIT_NO_EMPTY);

            $query->where(function ($q) use ($search, $words) {
                $folio = ltrim($search, '#');
                if ($folio !== '' && ctype_digit($folio)) {
                    $q->orWhere('orders.id', (int) $folio);
                }
                // Every word must match some field, so "Ana Pérez" matches name + last name.
                $q->orWhere(function ($all) use ($words) {
                    foreach ($words as $word) {
                        $like = $this->likePattern($word);
                        $all->where(function ($any) use ($like) {
                            foreach (['orders.code', 'orders.email_buyer', 'orders.name_buyer', 'orders.last_name_buyer'] as $column) {
                                $any->orWhereRaw($column . " LIKE ? ESCAPE '!'", [$like]);
                            }
                        });
                    }
                });
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
            'custom_email' => ['nullable', 'email', 'max:255'],
        ], [
            'order_ids.required' => 'Please select at least one order.',
            'order_ids.min' => 'Please select at least one order.',
            'order_ids.array' => 'The order selection is invalid.',
            'order_ids.max' => 'You can resend at most ' . self::MAX_ORDERS_PER_REQUEST . ' orders at once.',
            'order_ids.*.required' => 'The order selection is invalid.',
            'order_ids.*.integer' => 'The selected order folio is invalid.',
            'order_ids.*.distinct' => 'An order was selected more than once.',
            'custom_email.email' => 'The alternative email entered has an invalid format.',
            'custom_email.max' => 'The alternative email may not be greater than 255 characters.',
        ]);

        $orderIds = array_map('intval', $validated['order_ids']);
        $customEmail = !empty($validated['custom_email']) ? trim($validated['custom_email']) : null;

        // Only orders that belong to this event (orders -> tickets -> event).
        $orders = $event->orders()->with('ticket')->whereIn('orders.id', $orderIds)->get();
        if ($orders->count() !== count($orderIds)) {
            return redirect()->back(302, [], route('event.resend-emails', $event->id))
                ->with('error', 'Some selected orders do not belong to this event. No emails were sent.');
        }

        @set_time_limit(300);
        $result = $purchaseEmail->resendForOrders($event, $orders, $customEmail);

        $targetText = $customEmail ? " to {$customEmail}" : '';
        $message = "Resent emails{$targetText}: {$result['sent']} ({$result['sent_orders']} orders). Failed: {$result['failed']}.";
        $redirect = redirect()->back(302, [], route('event.resend-emails', $event->id));

        if ($result['failed'] > 0) {
            return $redirect->with('error', $message . ' Could not send to: ' . implode(', ', $result['failed_emails']));
        }

        return $redirect->with('succes', $message);
    }

    /**
     * "%term%" for LIKE ... ESCAPE '!': %, _ and ! are matched literally (same on MySQL and SQLite;
     * a backslash is a normal character because the escape character is '!').
     */
    private function likePattern(string $term): string
    {
        return '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $term) . '%';
    }
}
