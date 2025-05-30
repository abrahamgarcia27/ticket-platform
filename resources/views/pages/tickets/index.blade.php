@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tickets'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div id="alert">
            @include('components.alert')
        </div>
        <div class="card-body p-3">
            <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0">Tickets of Event {{ $event->title }}</h4>
                    <a href="{{ route('ticket.create', [$event->id]) }}" class="btn btn-primary btn-sm ms-auto">Create</a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Qunatity</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tickets Sold</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date and Time start</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)    
                            <tr>
                                <td style="padding-left: 24px">
                                    <p class="text-sm font-weight-bold mb-0">{{ $ticket->title }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $ticket->quantity }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $ticket->sold_tickets }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $ticket->date_time_start }}</p>
                                </td>
                                <td style="display:flex; justify-content:space-around;"><a href="{{ route('ticket.edit', [$ticket->id]) }}"><i class="fas fa-edit"></i></a><a href=""><i class="fas fa-eye"></i></a><a href="{{ route('order.ticket', [$ticket->id]) }}"><i class="fas fa-shopping-cart"></i></a><a class="btnDelete"><i class="fas fa-trash"></i></a><form class="frmDelete" method="POST"
                                    action="{{ route('ticket.destroy', $ticket->id) }}">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                </form></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>           
        </div>
    </div>
@endsection
@push('js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<script>
    $('.btnDelete').click(function(){
        if (confirm("Are you sure you want to delete this ticket?")) {
            $(this).parent().find(".frmDelete").submit();
        }
    });    
</script>
@endpush