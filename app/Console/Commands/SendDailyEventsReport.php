<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendDailyEventsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía por email el reporte de eventos cuando el date_time_end de sus tickets ha sido alcanzado (se ejecuta cada 5 minutos vía cron)';

    /**
     * Execute the console command.
     * Busca eventos cuyo último ticket ya pasó su date_time_end y aún no han recibido reporte.
     */
    public function handle(): int
    {
        $now = Carbon::now();

        // Eventos sin reporte enviado y con al menos un ticket cuyo date_time_end es hoy
        $eventsPendingReport = Event::whereNull('report_sent_at')
            ->whereHas('tickets', function ($query) {
                $query->whereDate('date_time_end', Carbon::today());
            })
            ->get();

        $eventIdsToReport = $eventsPendingReport->filter(function (Event $event) use ($now) {
            $lastTicketEnd = $event->tickets()->max('date_time_end');
            if ($lastTicketEnd === null) {
                return false;
            }
            return Carbon::parse($lastTicketEnd)->lte($now);
        })->pluck('id');

        if ($eventIdsToReport->isEmpty()) {
            $this->info('No hay eventos con tickets ya finalizados pendientes de reporte.');
            return Command::SUCCESS;
        }

        $events = Event::whereIn('id', $eventIdsToReport)->get();

        $events->each(function (Event $event) {
            $event->pdf_url = URL::temporarySignedRoute(
                'report.event.pdf',
                now()->addDays(7),
                ['id' => $event->id]
            );
        });

        $to = array_values(array_filter(array_map('trim', explode(',', config('mail.report_daily_to', '')))));

        Mail::send('pages.email.daily-events-report', ['events' => $events], function ($message) use ($to, $events) {
            $message->to($to);
            $message->subject(
                'Report: Events ended - ' . $events->count() . ' event(s) - ' . Carbon::now()->format('m/d/Y H:i')
            );
        });

        $events->each(function (Event $event) {
            $event->offsetUnset('pdf_url');
            $event->update(['report_sent_at' => Carbon::now()]);
        });

        $this->info('Reporte enviado a ' . implode(', ', $to) . ' con ' . $events->count() . ' evento(s).');

        return Command::SUCCESS;
    }
}
