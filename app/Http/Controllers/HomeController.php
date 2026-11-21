<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $weekStart = $this->resolveWeekStart($request);
        $weekEnd = $weekStart->addDays(6);

        $rows = $this->weeklySalesByEvent($weekStart);

        $sales = [
            'week' => $weekStart->format('o-\WW'),
            'start' => $weekStart,
            'end' => $weekEnd,
            'range_label' => $this->rangeLabel($weekStart, $weekEnd),
            'is_current' => $weekStart->equalTo($this->now()->startOfWeek(Carbon::MONDAY)),
            'prev_week' => $weekStart->subWeek()->format('o-\WW'),
            'next_week' => $weekStart->addWeek()->format('o-\WW'),
            'rows' => $rows,
            'totals' => [
                'events' => count($rows),
                'revenue' => round(array_sum(array_column($rows, 'revenue')), 2),
                'paid_tickets' => array_sum(array_column($rows, 'paid_tickets')),
                'free_tickets' => array_sum(array_column($rows, 'free_tickets')),
            ],
        ];

        return view('pages.dashboard', compact('sales'));
    }

    /**
     * Single source of "now" (app timezone). Carbon and CarbonImmutable keep separate test clocks in
     * Carbon 2, so everything goes through Carbon::now() (frozen with Carbon::setTestNow()).
     */
    private function now(): CarbonImmutable
    {
        return Carbon::now()->toImmutable();
    }

    /**
     * Monday 00:00 (app timezone) of the requested week.
     * Accepts ?week=YYYY-Www (ISO week) or ?date=YYYY-MM-DD; anything invalid falls back to the current week.
     */
    private function resolveWeekStart(Request $request): CarbonImmutable
    {
        $current = $this->now()->startOfWeek(Carbon::MONDAY);

        $week = $request->query('week');
        if (is_string($week) && preg_match('/^(\d{4})-W(\d{1,2})$/', trim($week), $m)) {
            $year = (int) $m[1];
            $number = (int) $m[2];
            if ($year >= 2000 && $year <= 2100 && $number >= 1) {
                $candidate = $this->now()->setISODate($year, $number)->startOfWeek(Carbon::MONDAY);
                // setISODate rolls week 53 over to the next year when that year has only 52 weeks.
                if ((int) $candidate->format('o') === $year && (int) $candidate->format('W') === $number) {
                    return $candidate;
                }
            }
        }

        $date = $request->query('date');
        if (is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($date))) {
            $parsed = CarbonImmutable::createFromFormat('!Y-m-d', trim($date));
            if ($parsed !== false && $parsed->format('Y-m-d') === trim($date) && $parsed->year >= 2000 && $parsed->year <= 2100) {
                return $parsed->startOfWeek(Carbon::MONDAY);
            }
        }

        return $current;
    }

    /**
     * Sales of the events whose start date falls in the week (Monday 00:00 to next Monday 00:00).
     * - revenue: sum of the ticket price of paid orders (orders with Stripe data). Fees are not included.
     * - paid_tickets / free_tickets: orders with / without Stripe data.
     * One order = one ticket. Events without orders are included with zeros.
     */
    private function weeklySalesByEvent(CarbonImmutable $weekStart): array
    {
        $from = $weekStart->format('Y-m-d 00:00');
        $to = $weekStart->addWeek()->format('Y-m-d 00:00');

        $rows = DB::table('events')
            ->leftJoin('tickets', 'tickets.event_id', '=', 'events.id')
            ->leftJoin('orders', 'orders.ticket_id', '=', 'tickets.id')
            // date_time_start is stored as "Y-m-d H:i" text in the app timezone, so string comparison is safe.
            ->where('events.date_time_start', '>=', $from)
            ->where('events.date_time_start', '<', $to)
            ->groupBy('events.id', 'events.title', 'events.date_time_start')
            ->orderBy('events.date_time_start')
            ->orderBy('events.id')
            ->select([
                'events.id',
                'events.title',
                'events.date_time_start',
                DB::raw('SUM(CASE WHEN orders.id IS NOT NULL AND orders.stripe_data IS NOT NULL THEN 1 ELSE 0 END) AS paid_tickets'),
                DB::raw('SUM(CASE WHEN orders.id IS NOT NULL AND orders.stripe_data IS NULL THEN 1 ELSE 0 END) AS free_tickets'),
                DB::raw("SUM(CASE WHEN orders.id IS NOT NULL AND orders.stripe_data IS NOT NULL THEN COALESCE(CAST(NULLIF(tickets.price, '') AS DECIMAL(10,2)), 0) ELSE 0 END) AS revenue"),
            ])
            ->get();

        return $rows->map(function ($row) {
            $start = CarbonImmutable::parse($row->date_time_start);

            return [
                'id' => (int) $row->id,
                'title' => (string) $row->title,
                'date_label' => ucfirst(str_replace('.', '', $start->locale('es')->isoFormat('ddd D MMM, HH:mm'))),
                'paid_tickets' => (int) $row->paid_tickets,
                'free_tickets' => (int) $row->free_tickets,
                'revenue' => round((float) $row->revenue, 2),
            ];
        })->all();
    }

    private function rangeLabel(CarbonImmutable $start, CarbonImmutable $end): string
    {
        $start = $start->locale('es');
        $end = $end->locale('es');

        if ($start->year !== $end->year) {
            return $start->isoFormat('D [de] MMMM [de] YYYY') . ' al ' . $end->isoFormat('D [de] MMMM [de] YYYY');
        }
        if ($start->month !== $end->month) {
            return $start->isoFormat('D [de] MMMM') . ' al ' . $end->isoFormat('D [de] MMMM [de] YYYY');
        }

        return $start->isoFormat('D') . ' al ' . $end->isoFormat('D [de] MMMM [de] YYYY');
    }
}
