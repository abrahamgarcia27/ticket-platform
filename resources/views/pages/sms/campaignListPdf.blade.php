<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Sms Campaigns</title>
    <style>
        body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; font-size: 10px; color: #000; }
        h1 { font-size: 16px; margin: 0 0 4px 0; }
        .meta { color: #555; margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; vertical-align: top; }
        th { background: #f0f0f0; text-transform: uppercase; font-size: 9px; }
        td.content { width: 45%; }
    </style>
</head>
<body>
    <h1>Sms Campaigns</h1>
    <p class="meta">
        Generated: {{ $generatedAt }} &middot; Total: {{ count($rows) }}
        @if ($search !== '')
            &middot; Search: "{{ $search }}"
        @endif
    </p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Sent At</th>
                <th>Contact List</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row[0] }}</td>
                    <td>{{ $row[1] }}</td>
                    <td class="content">{{ $row[2] }}</td>
                    <td>{{ $row[3] }}</td>
                    <td>{{ $row[4] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No campaigns found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
