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
    protected $description = 'Envía por email la lista de eventos que finalizaron el día anterior con enlaces para descargar el PDF de órdenes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $yesterday = Carbon::yesterday();

        $events = Event::whereDate('date_time_end', $yesterday)->get();

        $events->each(function (Event $event) {
            $event->pdf_url = URL::temporarySignedRoute(
                'report.event.pdf',
                now()->addDays(7),
                ['id' => $event->id]
            );
        });

        $to = config('mail.report_daily_to');

        Mail::send('pages.email.daily-events-report', ['events' => $events], function ($message) use ($to) {
            $message->to($to);
            $message->subject('Reporte diario: Eventos finalizados ayer - ' . Carbon::yesterday()->format('d/m/Y'));
        });

        $this->info('Reporte enviado a ' . $to . ' con ' . $events->count() . ' evento(s).');

        return Command::SUCCESS;
    }
}
