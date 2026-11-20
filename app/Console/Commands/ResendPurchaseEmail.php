<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\PurchaseEmailService;

class ResendPurchaseEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:resend-purchase
                            {ticket_id? : ID del ticket (o de la orden)}
                            {email? : Correo del comprador}
                            {--to= : Correo alternativo para enviar la prueba o reenvío}
                            {--dry-run : Genera el PDF y valida los datos sin enviar el correo}
                            {--save-pdf= : Ruta local donde guardar una copia del PDF generado (opcional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reenvía el correo de compra de tickets con el PDF adjunto de la orden';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $ticketIdInput = $this->argument('ticket_id') ?: $this->ask('Por favor, ingresa el ID del Ticket (o ID de Orden):');
        $emailInput = $this->argument('email') ?: $this->ask('Por favor, ingresa el correo del comprador:');

        $emailInput = trim((string) $emailInput);
        $ticketIdInput = trim((string) $ticketIdInput);

        if (empty($ticketIdInput) || empty($emailInput)) {
            $this->error('Debes proporcionar tanto el ID del ticket como el correo del comprador.');
            return Command::FAILURE;
        }

        // Detección inteligente: Si ingresaron un ID de orden en vez de ticket
        $resolvedTicketId = (int) $ticketIdInput;
        $ticket = Ticket::with(['event.user'])->find($resolvedTicketId);

        if (!$ticket) {
            $orderCandidate = Order::with(['ticket.event.user'])->find($resolvedTicketId);
            if ($orderCandidate && $orderCandidate->ticket) {
                $this->warn("El ID #{$ticketIdInput} corresponde a una Orden (#{$orderCandidate->id}). Se resolverá automáticamente al Ticket #{$orderCandidate->ticket_id}.");
                $ticket = $orderCandidate->ticket;
                $resolvedTicketId = $ticket->id;
                if (empty($emailInput)) {
                    $emailInput = $orderCandidate->email_buyer;
                }
            } else {
                $this->error("No se encontró ningún ticket ni orden con el ID #{$ticketIdInput}.");
                return Command::FAILURE;
            }
        }

        $this->info("Buscando órdenes para el Ticket #{$resolvedTicketId} ('{$ticket->title}') y comprador '{$emailInput}'...");

        // Buscar todas las órdenes de ese ticket y comprador
        $orders = Order::with(['ticket.event.user'])
            ->where('ticket_id', $resolvedTicketId)
            ->where('email_buyer', $emailInput)
            ->orderBy('id', 'asc')
            ->get();

        if ($orders->isEmpty()) {
            $this->error("No se encontraron órdenes para el Ticket #{$resolvedTicketId} y el correo '{$emailInput}'.");
            return Command::FAILURE;
        }

        // Agrupar órdenes por tanda de compra (sesión de Stripe o ventana de tiempo)
        $batches = [];
        foreach ($orders as $order) {
            $stripeSessionId = null;
            if (!empty($order->stripe_data)) {
                $stripeDecoded = json_decode($order->stripe_data, true);
                $stripeSessionId = $stripeDecoded['id'] ?? null;
            }

            // Agrupador: sesión de Stripe si existe, o marca de tiempo agrupada por 10 minutos
            $batchKey = $stripeSessionId ?: Carbon::parse($order->created_at)->format('Y-m-d H:i');
            $batches[$batchKey][] = $order;
        }

        // Si hay más de una tanda de compra, permitir seleccionar o elegir la última
        $selectedBatchKey = array_key_last($batches);
        if (count($batches) > 1 && $this->input->isInteractive()) {
            $this->info('Se encontraron múltiples tandas de compra para este usuario:');
            $choices = [];
            foreach ($batches as $k => $bOrders) {
                $count = count($bOrders);
                $firstO = $bOrders[0];
                $choices[$k] = "Tanda [{$k}] - {$count} ticket(s) - Fecha: {$firstO->created_at} - IDs: {$bOrders[0]->id} a {$bOrders[$count - 1]->id}";
            }
            $selectedBatchKey = $this->choice('¿Qué tanda de compra deseas reenviar?', array_keys($choices), $selectedBatchKey);
        }

        /** @var Order[] $batchOrders */
        $batchOrders = $batches[$selectedBatchKey];
        $totalTickets = count($batchOrders);

        $firstOrder = $batchOrders[0];
        $lastOrder = end($batchOrders);
        $event = $ticket->event;

        $this->table(
            ['Campo', 'Detalle'],
            [
                ['Evento', $event->title],
                ['Fecha Evento', $event->date_time_start],
                ['Ticket', $ticket->title . ' ($' . number_format($ticket->price, 2) . ')'],
                ['Comprador', $firstOrder->name_buyer . ' ' . $firstOrder->last_name_buyer],
                ['Email Comprador', $firstOrder->email_buyer],
                ['Total Tickets', $totalTickets],
                ['Rango IDs Orden', "#{$firstOrder->id} - #{$lastOrder->id}"],
                ['Tanda de Compra', $selectedBatchKey],
            ]
        );

        $purchaseEmail = app(PurchaseEmailService::class);
        $orders_data = $purchaseEmail->buildOrdersData($event, $batchOrders);

        $this->info('Renderizando PDF con los ' . count($orders_data) . ' tickets...');
        $pdfContent = $purchaseEmail->renderPdf($orders_data);
        $pdfSizeKb = round(strlen($pdfContent) / 1024, 2);

        $this->info("PDF generado exitosamente ({$pdfSizeKb} KB, {$totalTickets} tickets/páginas).");

        // Guardar copia local si fue solicitada
        if ($savePath = $this->option('save-pdf')) {
            file_put_contents($savePath, $pdfContent);
            $this->info("Copia del PDF guardada en: {$savePath}");
        }

        $targetEmail = $this->option('to') ?: $firstOrder->email_buyer;
        // Datos del email idénticos a StripeController (ver PurchaseEmailService)
        $emailData = $purchaseEmail->buildEmailData($event, collect($batchOrders), $targetEmail);

        if ($this->option('dry-run')) {
            $this->warn("MODO SIMULACIÓN (--dry-run): El correo NO fue enviado.");
            $this->info("Destinatario simulado: {$targetEmail}");
            $this->info("Asunto simulado: {$emailData['subject']}");
            $this->info("Total de tickets en el PDF adjunto: {$totalTickets}");
            return Command::SUCCESS;
        }

        $this->info("Enviando correo a: {$targetEmail}...");

        try {
            $purchaseEmail->send($emailData, $pdfContent);

            $this->info("¡Correo reenviado exitosamente a {$targetEmail} con el PDF de {$totalTickets} tickets adjunto!");

            Log::info('[tickets:resend-purchase] Correo reenviado con éxito', [
                'ticket_id' => $resolvedTicketId,
                'recipient' => $targetEmail,
                'buyer_email' => $firstOrder->email_buyer,
                'orders_count' => $totalTickets,
                'pdf_size_kb' => $pdfSizeKb,
            ]);

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $errorMsg = "Error al enviar el correo: " . $e->getMessage();
            $this->error($errorMsg);
            Log::error('[tickets:resend-purchase] ' . $errorMsg, [
                'exception' => $e,
            ]);
            return Command::FAILURE;
        }
    }
}
