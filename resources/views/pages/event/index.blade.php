@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Events'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div id="alert">
            @include('components.alert')
        </div>
        <div class="card-body p-3">
            <div class="card-header pb-0">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Show Past Events</label>
                </div>
                <div class="d-flex align-items-center">
                    <h4 class="mb-0">Events</h4>
                    <a href="{{ route('event.create') }}" class="btn btn-primary btn-sm ms-auto">Create</a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date and Time start</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tickets Sold</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)    
                            <tr>
                                <td style="padding-left: 24px">
                                    <p class="text-sm font-weight-bold mb-0">{{ $event->title }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $event->ubication }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $event->date_time_start }}</p>
                                </td>
                                @if ($event->totalTickets->isEmpty())
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ count($event->orders) . ' / 0' }}</p>
                                </td>  
                                @else
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ count($event->orders) . ' / ' . $event->totalTickets[0]->total_quantity }}</p>
                                </td>
                                @endif
                                <td style="display:flex; justify-content:space-around;"><a href="#" onclick="confirmClone({{ $event->id }})"><i class="fas fa-clone"></i></a><a href="{{ route('event.edit', [$event->id]) }}"><i class="fas fa-edit"></i></a><a href="{{ route('event.show', [$event->id]) }}"><i class="fas fa-eye"></i></a><a href="{{ route('ticket.index', [$event->id]) }}"><i class="fas fa-ticket-alt"></i></a><a href="{{ route('order.event', [$event->id]) }}"><i class="fas fa-shopping-cart"></i></a><a href="{{ route('event.resend-emails', [$event->id]) }}" title="Resend purchase emails" aria-label="Resend purchase emails"><i class="fas fa-envelope"></i></a><a class="btnDelete"><i class="fas fa-trash"></i></a><form class="frmDelete" method="POST"
                                    action="{{ route('event.destroy', $event->id) }}">
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
        if (confirm("Are you sure you want to delete this event?")) {
            $(this).parent().find(".frmDelete").submit();
        }
    });    
    
    function confirmClone(eventId) {
        if (confirm('¿Are you sure you want to clone this event?')) {
            // Si el usuario confirma, redirigir a la ruta de clonación
            window.location.href = "{{ route('event.clonar', '') }}/" + eventId;
        }
    }
   
    document.addEventListener('DOMContentLoaded', function() {
        const switchElement = document.getElementById('flexSwitchCheckDefault');

        // Verificar si el parámetro past-orders está presente en la URL
        const urlParams = new URLSearchParams(window.location.search);
        const pastOrdersParam = 'past-events';
        const hasPastOrdersParam = urlParams.has(pastOrdersParam);

        // Establecer el estado del interruptor según la presencia del parámetro
        switchElement.checked = hasPastOrdersParam;
    });
    document.addEventListener('DOMContentLoaded', function() {
        const switchElement = document.getElementById('flexSwitchCheckDefault');

        switchElement.addEventListener('change', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const pastEventsParam = 'past-events';

            if (this.checked) {
                // Agregar el parámetro past_events a la URL
                urlParams.set(pastEventsParam, '');
            } else {
                // Eliminar el parámetro past_events de la URL
                urlParams.delete(pastEventsParam);
            }

            // Redirigir a la URL actual con los parámetros actualizados
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        });
    });
</script>
@endpush