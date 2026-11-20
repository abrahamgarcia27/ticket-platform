<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Sms Campaigns</title>
    <style>
        @page { margin: 28px 28px 32px 28px; }
        body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; font-size: 9px; color: #000; }
        h1 { font-size: 16px; margin: 0 0 4px 0; }
        .meta { color: #555; margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td {
            border: 1px solid #ccc;
            padding: 4px 8px 4px 5px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
            overflow: hidden;
        }
        th { background: #f0f0f0; text-transform: uppercase; font-size: 8px; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        .col-id { width: 7%; }
        .col-title { width: 16%; }
        .col-content { width: 51%; }
        .col-sent { width: 12%; }
        .col-list { width: 14%; }
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
        <colgroup>
            <col class="col-id">
            <col class="col-title">
            <col class="col-content">
            <col class="col-sent">
            <col class="col-list">
        </colgroup>
        <thead>
            <tr>
                <th class="col-id">ID</th>
                <th class="col-title">Title</th>
                <th class="col-content">Content</th>
                <th class="col-sent">Sent At</th>
                <th class="col-list">Contact List</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td class="col-id">{{ $row[0] }}</td>
                    <td class="col-title">{{ $row[1] }}</td>
                    <td class="col-content">{!! nl2br(e($row[2])) !!}</td>
                    <td class="col-sent">{{ $row[3] }}</td>
                    <td class="col-list">{{ $row[4] }}</td>
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
