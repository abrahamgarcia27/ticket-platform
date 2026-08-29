<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Orders of Event {{ $event->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { font-size: 16px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Orders of Event {{ $event->title }}</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Ticket</th>
                <th>Ticket type</th>
                <th>Price</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($event->orders as $order)
            <tr>
                <td>{{ $order->name_buyer }} {{ $order->last_name_buyer }}</td>
                <td>{{ $order->email_buyer }}</td>
                <td>{{ $order->phone_buyer }}</td>
                <td>{{ $order->ticket->title }}</td>
                <td>{{ $order->stripe_data == null ? 'Free' : 'Paid' }}</td>
                <td>{{ $order->ticket->price }}</td>
                <td>{{ $order->code }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
