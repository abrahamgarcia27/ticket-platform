<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\Order;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    private const CAMPAIGNS_PER_PAGE = 10;

    private const SHORTYSMS_API_BASE = 'https://shortysms.com/api';

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $campaigns = $this->filterCampaigns($this->fetchCampaigns(), $search);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $lastPage = max(1, (int) ceil($campaigns->count() / self::CAMPAIGNS_PER_PAGE));
        if ($page > $lastPage) {
            // Out of range page: go to the last page, keeping the search.
            return redirect()->route('sms.index', array_filter([
                'search' => $search,
                'page' => $lastPage > 1 ? $lastPage : null,
            ]));
        }

        $campaigns = new LengthAwarePaginator(
            $campaigns->forPage($page, self::CAMPAIGNS_PER_PAGE)->values(),
            $campaigns->count(),
            self::CAMPAIGNS_PER_PAGE,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'pageName' => 'page']
        );
        $campaigns->withQueryString();

        return view('pages.sms.campaignList', compact('campaigns', 'search'));
    }

    public function exportCsv(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $rows = $this->campaignExportRows($this->filterCampaigns($this->fetchCampaigns(), $search));
        $filename = 'sms-campaigns-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM so Excel shows accents correctly.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Title', 'Content', 'Sent At', 'Contact List']);
            foreach ($rows as $row) {
                fputcsv($out, array_map([$this, 'escapeCsvCell'], $row));
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportPdf(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $rows = $this->campaignExportRows($this->filterCampaigns($this->fetchCampaigns(), $search));
        // DejaVu Sans has no emoji glyphs (they render as empty boxes), so strip them from the PDF only.
        $rows = array_map(function ($row) {
            return array_map([$this, 'stripEmoji'], $row);
        }, $rows);

        $pdf = Pdf::loadView('pages.sms.campaignListPdf', [
            'rows' => $rows,
            'search' => $this->stripEmoji($search),
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('sms-campaigns-' . now()->format('Ymd-His') . '.pdf');
    }

    /**
     * All campaigns from ShortySMS (follows the API pagination), only real campaigns.
     */
    private function fetchCampaigns(): Collection
    {
        $access_token = env('SHORTYSMS_TOKEN');
        $campaigns = collect();
        $url = self::SHORTYSMS_API_BASE . '/campaigns';
        $requests = 0;

        while ($url && $requests < 50) {
            $requests++;
            $response = Http::withToken($access_token)->withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->get($url);
            $response = $response->collect()->toArray();

            if (!array_key_exists('data', $response) || !is_array($response['data'])) {
                break;
            }
            $campaigns = $campaigns->merge($response['data']);
            $url = $response['next_page_url'] ?? null;
            if ($url && !$this->isShortySmsApiUrl($url)) {
                // Never send the bearer token to a host we don't trust.
                Log::warning('ShortySMS next_page_url points to an unexpected host, pagination stopped.', ['url' => $url]);
                $url = null;
            }
        }

        return $campaigns->filter(function ($campaign) {
            return ($campaign['is_campaign'] ?? false) === true;
        })->values();
    }

    /**
     * Case-insensitive search by id, title, content and contact list name.
     */
    private function filterCampaigns(Collection $campaigns, string $search): Collection
    {
        if ($search === '') {
            return $campaigns;
        }
        $needle = mb_strtolower($search);

        return $campaigns->filter(function ($campaign) use ($needle) {
            $haystack = [
                (string) ($campaign['id'] ?? ''),
                $campaign['title'] ?? '',
                $campaign['content'] ?? '',
                $this->campaignContactLists($campaign),
            ];
            foreach ($haystack as $value) {
                if (str_contains(mb_strtolower((string) $value), $needle)) {
                    return true;
                }
            }
            return false;
        })->values();
    }

    private function campaignContactLists(array $campaign): string
    {
        return collect($campaign['contact_lists'] ?? [])->pluck('name')->filter()->implode(', ');
    }

    private function campaignExportRows(Collection $campaigns): array
    {
        return $campaigns->map(function ($campaign) {
            return [
                $campaign['id'] ?? '',
                $campaign['title'] ?? '',
                $campaign['content'] ?? '',
                !empty($campaign['sent_at']) ? date('Y-m-d H:i:s', strtotime($campaign['sent_at'])) : '',
                $this->campaignContactLists($campaign),
            ];
        })->all();
    }

    /**
     * True only when the URL has the same scheme and host as the ShortySMS API base.
     */
    private function isShortySmsApiUrl($url): bool
    {
        if (!is_string($url)) {
            return false;
        }
        $base = parse_url(self::SHORTYSMS_API_BASE);
        $next = parse_url($url);

        return is_array($next)
            && isset($next['scheme'], $next['host'])
            && strtolower($next['scheme']) === strtolower($base['scheme'])
            && strtolower($next['host']) === strtolower($base['host'])
            && ($next['port'] ?? null) === ($base['port'] ?? null)
            && !isset($next['user']);
    }

    /**
     * Remove emoji (pictographs, symbols, flags, skin tones, variation selectors, ZWJ, keycaps, tags)
     * while keeping accents, ñ and the rest of the text.
     */
    private function stripEmoji($value): string
    {
        $value = (string) $value;
        $value = preg_replace(
            '/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2300}-\x{23FF}\x{2B00}-\x{2BFF}\x{FE00}-\x{FE0F}\x{200D}\x{20E3}\x{E0020}-\x{E007F}\x{3030}\x{303D}\x{3297}\x{3299}]/u',
            '',
            $value
        ) ?? $value;

        // Collapse the spaces left behind (keeping line breaks).
        $value = preg_replace('/[ \t]{2,}/u', ' ', $value) ?? $value;
        $value = preg_replace('/^[ \t]+|[ \t]+$/mu', '', $value) ?? $value;

        return trim($value);
    }

    /**
     * Avoid CSV formula injection when the file is opened in Excel.
     */
    private function escapeCsvCell($value): string
    {
        $value = (string) $value;
        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'" . $value;
        }
        return $value;
    }

    public function create() 
    {
        $access_token = env('SHORTYSMS_TOKEN');
        
        $response = Http::withToken($access_token)->withHeaders([
            'Content-Type' => 'application/json'
        ])
        ->get('https://shortysms.com/api/contact-lists');
        $response = $response->collect();
        $response = $response->toArray();
        if (array_key_exists('data', $response)) {
            $contact_lists = $response['data'];
        } else {
            $contact_lists = [];
        }
        
        return view('pages.sms.createCampaign', compact('contact_lists'));
    }

    public function addCampaingStore(Request $request) {

        // dd($request);
        $utc_date = Carbon::now()->utc();
        $utc_date = $utc_date->addMinute(); 
        $utc_date = $utc_date->format('Y-m-d\TH:i:s\Z'); 
        
        $access_token = env('SHORTYSMS_TOKEN');
        
        $response = Http::withToken($access_token)->withHeaders([
            'Content-Type' => 'application/json'
        ])
        ->post('https://shortysms.com/api/campaigns', [
            'title' => $request->title,
            'content' => $request->message,
            'contact_list_id' => $request->contact_list,
            'from_phone_number' => '(415) 639-3265',
            'scheduled_at' => $utc_date
        ]);
        $response = $response->collect();
        $response = $response->toArray();
        return redirect('/sms')->with('succes', 'Campaign succesfully creted.');

    }

    public function indexContactList() 
    {
        $access_token = env('SHORTYSMS_TOKEN');
        
        $response = Http::withToken($access_token)->withHeaders([
            'Content-Type' => 'application/json'
        ])
        ->get('https://shortysms.com/api/contact-lists');
        $response = $response->collect();
        $response = $response->toArray();
        if (array_key_exists('data', $response)) {
            $contact_lists = $response['data'];
        } else {
            $contact_lists = [];
        }

        return view('pages.sms.contactlist', compact('contact_lists'));
    }
    public function createContactList() 
    {
        return view('pages.sms.createContactlist');
    }
    public function storeContactList(Request $request) 
    {
        $access_token = env('SHORTYSMS_TOKEN');
        
        $response = Http::withToken($access_token)->withHeaders([
            'Content-Type' => 'application/json'
        ])
        ->post('https://shortysms.com/api/contact-lists', [
            'name' => $request->title
        ]);
        $response = $response->collect();
        $response = $response->toArray();
        if(array_key_exists('contactList', $response)){
            return redirect('/contact-list')->with('succes', 'Contact List succesfully created.');
        } else {
            return redirect('/contact-list')->with('error', 'Some error has occurred.');
            
        }
    }
    public function addContactsTo($id, $name) 
    {
        $events = Event::all();
        $tickets = Ticket::all();
        return view('pages.sms.add-contacts', compact('id', 'name', 'events', 'tickets'));
    }
    public function addCcontactsToStore(Request $request, $id, $name) 
    {
        // dd($request);
        $access_token = env('SHORTYSMS_TOKEN');

        if ($request->type == 'event') {
            $event = Event::where('id', $request->events)->first();
            $orders = $event->orders->unique('phone_buyer');
            foreach ($orders as $order) {
                $response = Http::withToken($access_token)->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post('https://shortysms.com/api/contacts', [
                    'email' => $order->email_buyer,
                    'first_name' => $order->name_buyer,
                    'last_name' => $order->last_name_buyer,
                    'phone' => $order->phone_buyer,
                    'contact_list_id' => $id
                ]);
            }
        } else {
            $orders = Order::where('ticket_id', $request->tickets)->get();
           
            foreach ($orders as $order) {
                $response = Http::withToken($access_token)->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post('https://shortysms.com/api/contacts', [
                    'email' => $order->email_buyer,
                    'first_name' => $order->name_buyer,
                    'last_name' => $order->last_name_buyer,
                    'phone' => $order->phone_buyer,
                    'contact_list_id' => $id
                ]);
            }
        }
        return redirect('/contact-list')->with('succes', 'Contacts added to ' . $name);
        
    }
    public function sendCampaign() 
    {
        dd('api');
    }
}
