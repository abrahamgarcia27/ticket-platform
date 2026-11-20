@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Sms Campaigns'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div id="alert">
            @include('components.alert')
        </div>
        <div class="card-body p-3">
            <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0">Sms Campaigns</h4>
                    <a href="{{ route('sms.create') }}" class="btn btn-primary btn-sm ms-auto">Create</a>
                </div>
                <div class="row align-items-center mt-3">
                    <div class="col-md-7">
                        <form method="GET" action="{{ route('sms.index') }}" class="d-flex align-items-center" role="search">
                            <input type="search" name="search" class="form-control form-control-sm me-2" maxlength="100"
                                placeholder="Search by ID, title, content or contact list" value="{{ $search }}" aria-label="Search campaigns">
                            <button type="submit" class="btn btn-dark btn-sm mb-0 me-2">Search</button>
                            @if ($search !== '')
                                <a href="{{ route('sms.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Clear</a>
                            @endif
                        </form>
                    </div>
                    <div class="col-md-5 d-flex justify-content-md-end mt-2 mt-md-0">
                        <a href="{{ route('sms.export.csv', array_filter(['search' => $search])) }}" class="btn btn-outline-primary btn-sm mb-0 me-2">Export CSV</a>
                        <a href="{{ route('sms.export.pdf', array_filter(['search' => $search])) }}" class="btn btn-outline-primary btn-sm mb-0">Export PDF</a>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Content</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Sent At</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Contact List</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($campaigns as $campaign)
                                <tr>
                                    <td style="padding-left: 24px">
                                        <p class="text-sm font-weight-bold mb-0">{{ $campaign['id'] ?? '' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $campaign['title'] ?? '' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $campaign['content'] ?? '' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ !empty($campaign['sent_at']) ? date('Y-m-d H:i:s', strtotime($campaign['sent_at'])) : '' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ collect($campaign['contact_lists'] ?? [])->pluck('name')->filter()->implode(', ') }}</p>
                                    </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-sm mb-0">{{ $search !== '' ? 'No campaigns match your search.' : 'No campaigns found.' }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($campaigns->total() > 0)
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between px-4 pt-3">
                        <p class="text-sm text-secondary mb-2 mb-md-0">
                            Showing {{ $campaigns->firstItem() }} to {{ $campaigns->lastItem() }} of {{ $campaigns->total() }} campaigns
                        </p>
                        {{ $campaigns->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>           
        </div>
    </div>
@endsection
@push('js')

<script>
     
</script>
@endpush