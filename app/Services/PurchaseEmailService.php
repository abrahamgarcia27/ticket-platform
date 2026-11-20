<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PDF;

/**
 * Builds and sends the purchase confirmation email (pages.email.email + Order.pdf attachment).
 * Shared by the `tickets:resend-purchase` command and the "Reenviar emails de compra" view.
 */
class PurchaseEmailService
{
    /**
     * One entry per order, used by pages.orders.pdf (one page per ticket).
     */
    public function buildOrdersData(Event $event, iterable $orders): array
    {
        $orders_data = [];
        foreach ($orders as $order) {
            $orders_data[] = [
                'event_title' => $event->title,
                'event_ubication' => $event->ubication,
                'event_datetime' => $event->date_time_start,
                'order' => $order->id,
                'type_ticket' => $order->ticket->type,
                'name_ticket' => $order->ticket->title,
                'name_buyer' => $order->name_buyer . ' ' . $order->last_name_buyer,
                'order_date' => Carbon::parse($order->created_at)->subHours(6),
                'qr' => $order->code,
                'website' => $event->user->web_url ?? ''
            ];
        }

        return $orders_data;
    }

    public function renderPdf(array $ordersData): string
    {
        return PDF::loadView('pages.orders.pdf', ['orders_data' => $ordersData])->output();
    }

    /**
     * Data for pages.email.email, same format used by the purchase flow / resend command.
     */
    public function buildEmailData(Event $event, Collection $orders, string $targetEmail): array
    {
        $orders = $orders->sortBy('id')->values();
        $firstOrder = $orders->first();
        $lastOrder = $orders->last();

        $startDate = ucwords(Carbon::parse($event->date_time_start)->locale('es')->isoFormat('D [de] MMMM, YYYY'));
        $title = $event->title . ' - ' . $startDate . ' ' . date('(h:s a)', strtotime($event->date_time_start));
        $clock = $startDate . ' ' . date(' h:s a', strtotime($event->date_time_start));
        $location = $event->ubication . ' ' . $event->street_address . ', ' .
            $event->address_locality . ', ' . $event->address_region . ' ' .
            $event->postal_code . ', ' . $event->address_country;

        $ticketsSummary = $orders->groupBy('ticket_id')->map(function (Collection $ticketOrders) {
            $ticket = $ticketOrders->first()->ticket;
            return [
                'ticket_id' => $ticket->id,
                'quantity' => $ticketOrders->count(),
                'title' => $ticket->title,
                'price' => $ticket->price,
            ];
        })->values()->all();

        return [
            'name' => $firstOrder->name_buyer,
            'email' => $targetEmail,
            'subject' => $event->title,
            'title' => $title,
            'clock' => $clock,
            'location' => $location,
            'order_id' => $lastOrder->id,
            'order_date' => Carbon::parse($firstOrder->created_at)->subHours(6),
            'order_quantity' => $orders->count(),
            'user_name' => $event->user->username ?? config('mail.from.name', 'Tickets Platform'),
            'user_email' => $event->user->email ?? config('mail.from.address'),
            'event_image' => $event->image,
            'organizer_image' => $event->user->image ?? '',
            'event_location' => $event->maps_url,
            'tickets' => $ticketsSummary
        ];
    }

    public function send(array $emailData, string $pdfContent): void
    {
        Mail::send('pages.email.email', $emailData, function ($message) use ($emailData, $pdfContent) {
            $fromAddress = config('mail.from.address') ?: env('MAIL_FROM_ADDRESS');
            $fromName = $emailData['user_name'];

            $message->from($fromAddress, $fromName);
            $message->to($emailData['email'], $emailData['name']);
            $message->subject($emailData['subject']);
            $message->priority(3);
            $message->attachData($pdfContent, 'Order.pdf', [
                'mime' => 'application/pdf',
            ]);
        });
    }

    /**
     * Resend the purchase email for the given orders of an event.
     * Orders are grouped by buyer email, so each buyer gets one email with all of their selected tickets.
     * If $overrideEmail is provided, all groups are delivered to that single email address instead.
     *
     * @return array{sent: int, failed: int, sent_orders: int, failed_emails: array<int, string>}
     */
    public function resendForOrders(Event $event, Collection $orders, ?string $overrideEmail = null): array
    {
        $result = ['sent' => 0, 'failed' => 0, 'sent_orders' => 0, 'failed_emails' => []];

        $overrideEmail = is_string($overrideEmail) ? trim($overrideEmail) : null;
        if ($overrideEmail === '') {
            $overrideEmail = null;
        }

        $groups = $orders->groupBy(fn (Order $order) => mb_strtolower(trim((string) $order->email_buyer)));

        foreach ($groups as $email => $buyerOrders) {
            $recipient = $overrideEmail ?? trim((string) $buyerOrders->first()->email_buyer);
            try {
                $pdfContent = $this->renderPdf($this->buildOrdersData($event, $buyerOrders->sortBy('id')));
                $emailData = $this->buildEmailData($event, $buyerOrders, $recipient);
                $this->send($emailData, $pdfContent);

                $result['sent']++;
                $result['sent_orders'] += $buyerOrders->count();
                Log::info('[resend-purchase-view] Correo reenviado', [
                    'event_id' => $event->id,
                    'recipient' => $emailData['email'],
                    'original_buyer_email' => $email,
                    'override_email' => $overrideEmail,
                    'order_ids' => $buyerOrders->pluck('id')->all(),
                    'user_id' => auth()->id(),
                ]);
            } catch (\Throwable $e) {
                $result['failed']++;
                $result['failed_emails'][] = (string) $recipient;
                Log::error('[resend-purchase-view] Error al reenviar correo: ' . $e->getMessage(), [
                    'event_id' => $event->id,
                    'recipient' => $recipient,
                    'original_buyer_email' => $email,
                    'override_email' => $overrideEmail,
                    'order_ids' => $buyerOrders->pluck('id')->all(),
                    'exception' => $e,
                ]);
            }
        }

        return $result;
    }
}
