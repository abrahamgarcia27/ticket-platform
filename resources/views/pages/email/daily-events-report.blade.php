<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        h1 { font-size: 20px; margin-bottom: 20px; color: #344767; }
        p { margin-bottom: 16px; }
        ul { list-style: none; padding: 0; margin: 0; }
        li { margin-bottom: 12px; padding: 12px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #5e72e4; }
        a { color: #5e72e4; text-decoration: none; font-weight: 500; }
        a:hover { text-decoration: underline; }
        .event-title { font-weight: bold; margin-bottom: 4px; }
        .no-events { color: #6c757d; font-style: italic; }
        .footer { margin-top: 24px; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Daily report: Events that ended yesterday</h1>
        <p>List of events that ended the previous day. You can download the orders PDF for each event using the link below.</p>

        @if(count($events) > 0)
            <ul>
                @foreach($events as $event)
                    <li>
                        <span class="event-title">{{ $event->title }}</span><br>
                        <a href="{!! $event->pdf_url !!}" target="_blank">Download orders PDF</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="no-events">There were no events that ended the previous day.</p>
        @endif

        <p class="footer">This is an automated email from Ticket Platform. Download links expire in 7 days.</p>
    </div>
</body>
</html>
