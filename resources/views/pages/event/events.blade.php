@extends('layouts.app')

@section('content')
    <main class="main-content mt-0 events-main">
        <div class="header">
            <a href="/list-events">
                <img src="{{ asset('img/logos/logo.png') }}" alt="Logo" class="logo">
            </a>
        </div>
        <div class="container events-container">
            <div class="d-flex justify-content-center mt-5 mb-5">
                <h4 class="subheader">UPCOMING EVENTS</h4>
            </div>
            <div class="d-flex row">
                    @foreach ($events as $event)
                        <div class="col-lg-4 col-md-6 col-sm-12 d-flex justify-content-center">
                            <a class="hover-event-list" href="{{ route('event.show', [$event->id])}}">
                                <div class="card-transparent list-event">
                                    <div class="image-container">
                                        <img src="{{ asset('storage/' .  $event->coverimage) }}" class="card-img-top" alt="...">
                                    </div>
                                    <!-- Event info moved outside and below the image -->
                                    <div class="event-info-container">
                                        <div class="event-date-circle">
                                            <p class="event-date mt-2">{{ strtoupper(\Carbon\Carbon::parse($event->date_time_start)->format('D d')) }}</p>
                                            <p class="event-date">{{ strtoupper(\Carbon\Carbon::parse($event->date_time_start)->format('M')) }}</p>
                                        </div>
                                        <div class="event-title">{{ $event->title }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
    @include('layouts.footers.guest.footer')
@endsection
@push('js')
<script>
  
</script>
@endpush
