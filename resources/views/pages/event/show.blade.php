@extends('layouts.app')

@section('content')
    <main class="main-content mt-0">
        <div class="header">
            <a href="/list-events">
                <img src="{{ asset('img/logos/logo.png') }}" alt="Logo" class="logo">
            </a>
        </div>
        <div id="alert">
            @include('components.alert')
        </div>
        <div class="card-transparent shadow-lg mt-0">
            <div class="card-body p-3">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">{{ date('j F, Y', strtotime($event->date_time_start)) }}</h5>
                    </div>
                    <div class="d-flex align-items-center">
                        <h3 class="mb-0">{{ $event->title }}</h3>
                    </div>
                    <div class="d-flex align-items-center" style="padding-top: 20px; text-align:justify;">
                        <h6 class="mb-0">{{ $event->summary }}</h6>
                    </div>
                </div> 
                <div class="card-body pb-0">                 
                    <nav class="navbar sticky-top navbar-dark nav-event">
                        <div class="col-6" id="navDesktop">
                            <ul class="nav justify-content-around" id="mi-ul">
                                <li class="nav-item">
                                    <a class="nav-link" id="aInfo" href="#whenandwhere">Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="aDetails" href="#about">Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="aOrganizer" href="#organizer">Organizer</a>
                                </li>
                            </ul>  
                        </div>
                        <div class="col-12" id="navPhone">
                            <ul class="nav justify-content-around">
                                <li class="nav-item">
                                    <a class="nav-link" id="aInfo2" href="#whenandwhere">Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="aDetails2" href="#about">Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="aOrganizer2" href="#organizer">Organizer</a>
                                </li>
                            </ul>  
                        </div>
                    </nav>            
                    <div class="row" id="whenandwhere">
                        <div class="col-6" id="whDesktop">
                            <div class="row">
                                <h4 style="padding-bottom: 20px">When and where</h4>
                                <div class="col-sm-4">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <i class="far fa-calendar" style="padding-right: 10px"></i>
                                            <h6 class="mb-0">Date</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ date('j F, Y ', strtotime($event->date_time_start)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <i class="fas fa-door-open" style="padding-right: 10px"></i>
                                            <h6 class="mb-0">Doors</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ date('h:s a', strtotime($event->date_time_start)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <a class="" href="{{ $event->maps_url }}" target="_blank"><i class="fas fa-map-pin" style="padding-right: 10px"></i></a>
                                            <a href="{{ $event->maps_url }}" target="_blank"><h6 class="mb-0">Location</h6></a>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ $location  }}</p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="col-12" id="whPhone">
                            <div class="row">
                                <h4 style="padding-bottom: 20px">When and where</h4>
                                <div class="col-sm-4">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <i class="far fa-calendar" style="padding-right: 10px"></i>
                                            <h6 class="mb-0">Date</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ date('j F, Y', strtotime($event->date_time_start)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 locationDiv">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <i class="fas fa-door-open" style="padding-right: 10px"></i>
                                            <h6 class="mb-0">Doors</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ date('h:s a', strtotime($event->date_time_start)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 locationDiv">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <a class="" href="{{ $event->maps_url }}" target="_blank"><i class="fas fa-map-pin" style="padding-right: 10px"></i></a>
                                            <a href="{{ $event->maps_url }}" target="_blank"><h6 class="mb-0">Location</h6></a>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ $location }}</p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="col-6 d-flex align-items-center justify-content-end" id="cardGetTickets">
                            @if ($tickets->isNotEmpty())    
                            <div class="card-transpatent" style="width: 20rem;">
                                @php
                                    $hasAvailableTickets = $tickets->some(function($ticket) use ($today) {
                                        return $today < $ticket->date_time_end && $ticket->orders_count < $ticket->quantity;
                                    });
                                    
                                    $lowestPaidPrice = $tickets->where('type', 'paid')
                                        ->where('date_time_end', '>', $today)
                                        ->min('price');
                                    
                                    $hasFreeTickets = $tickets->where('type', 'free')
                                        ->where('date_time_end', '>', $today)
                                        ->isNotEmpty();
                                @endphp

                                @if ($hasAvailableTickets)
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center">
                                            @if ($hasFreeTickets && !$lowestPaidPrice || $lowestPaidPrice == 0)
                                                <h4>Free</h4>
                                            @elseif ($lowestPaidPrice)
                                                <h4>From ${{ number_format($lowestPaidPrice, 2) }}</h4>
                                            @endif
                                        </div>
                                        <div class="d-grid gap-2" style="padding-top: 10px">
                                            <button type="button" class="btn btn-yellow" data-bs-toggle="modal" data-bs-target="#getTickets">
                                                Get Tickets
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <h4>Sales Ended</h4>
                                        </div>
                                        <div class="d-grid gap-2" style="padding-top: 10px">
                                            <button type="button" class="btn btn-yellow" data-bs-toggle="modal" data-bs-target="#">
                                                Get Details
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="card-transpatent" style="width: 20rem;">
                                <div class="card-body">
                                    <div class="d-grid gap-2" style="padding-top: 10px">
                                        <a type="button" class="btn btn-yellow" href="{{ $event->link_external_sales }}" target="_blank">
                                            Get Tickets
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        </div>
                        
                    </div>
                    <div class="row" style="padding-top: 40px" id="about">
                        <div class="card-transparent" style="width: 40rem">
                            <h4 style="padding-bottom: 20px">About this event</h4>  
                            <img src="{{ asset('storage/' .  $event->image) }}" class="card-img-bottom" alt="">
                            <div class="about-html">{!! $event->about !!}</div>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 40px" id="organizer">
                        <div class="card-transparent" style="width: 40rem">                          
                            <h4 style="padding-bottom: 20px">Organizer</h4>                           
                            <div class="card-body" >
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="avatar avatar-xl">
                                        <img src="{{ asset('storage/' .  $event->user->image) }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center" style="padding-top: 20px">
                                <p class="mb-0" style="font-size: 0.80rem">Organized by</p>  
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                <h6>{{ $event->user->username }}</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-center" style="padding-top: 40px">
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    Contact
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 40px" id="organizer">
                        <div class="card-transparent" style="width: 40rem">                          
                            <h4 style="padding-bottom: 20px">Other events you may like</h4>                           
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center" style="padding-top: 40px">
                                    <a href="/list-events" class="btn btn-dark">View Events</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
            <!-- Modal Contact -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-border">
                    <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Contact Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <h5>Phone</h5>
                                <p>{{ $event->user->phone }}</p>
                            </div>
                            <div class="col-6">
                                <h5>Email</h5>
                                <p>{{ $event->user->email }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <h5>Social Networks</h5>
                            <div class="d-flex align-items-center justify-content-around">
                                <a href="{{ $event->user->facebook_url }}"><i class="fab fa-facebook-f"></i></a>
                                <a href="{{ $event->user->instagram_url }}"><i class="fab fa-instagram"></i></a>
                                <a href="{{ $event->user->web_url }}"><i class="fas fa-globe"></i></a>
                            </div>
                        </div>
                      </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
            @if ($ticket != null)    
            <!-- Modal GetTickets-->
            <div class="modal fade" id="getTickets" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content modal-border">
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-center" style="padding-top: 15px">
                                        <h6>{{ $event->title }}</h6>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="mb-0" style="font-size: 0.80rem">{{ date('j F, Y (h:s a)', strtotime($event->date_time_start)) . ' - ' . date('j F, Y (h:s a)', strtotime($event->date_time_end)) }}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="modal-body" style="padding-top: 60px">
                                @foreach($tickets as $ticket)
                                    @if ($today < $ticket->date_time_end && $ticket->count_orders < $ticket->quantity)
                                    <div class="row mb-4">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center justify-content-start">
                                                <h6>{{ $ticket->title }}</h6>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <p class="mb-0" style="font-size: 0.80rem"><strong>{{ $ticket->type }}</strong></p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <p class="mb-0" style="font-size: 0.80rem">Sales end on {{ date('j F, Y (h:s a)', strtotime($ticket->date_time_end)) }}</p>
                                            </div>
                                            @if (($ticket->quantity - $ticket->count_orders) <= 10)      
                                            <div class="d-flex align-items-center justify-content-start">
                                                <p class="mb-0 text-danger" style="font-size: 0.80rem">Only {{ $ticket->quantity - $ticket->count_orders }} left</p>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-3">
                                            <div class="d-flex flex-column align-items-end">
                                                <h6 class="mb-2">
                                                    @if ($ticket->type == 'free')
                                                        Free
                                                    @else
                                                        ${{ number_format($ticket->price, 2) }}
                                                    @endif
                                                </h6>
                                                <select class="form-select mb-2 ticket-select" data-ticket-id="{{ $ticket->id }}" data-ticket-price="{{ $ticket->price }}" data-ticket-title="{{ $ticket->title }}" data-ticket-type="{{ $ticket->type }}">
                                                    @for($i = 0; $i <= min(10, $ticket->quantity - $ticket->count_orders); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-dark" data-bs-target="#checkout" onclick="if(validateTicketSelection(event)) { $('#getTickets').modal('hide'); $('#checkout').modal('show'); }">Checkout</button>
                        </div>
                        <div class="col-4">
                            <div class="d-flex align-items-center justify-content-end">
                                <a type="button" style="padding-right: 5px;" data-bs-dismiss="modal"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="card" style="max-height: 200px">
                                <div class="image-container" style="max-height: 200px; overflow:hidden;">
                                    <img src="{{ asset('storage/' .  $event->image) }}" style="height:auto;" class="card-img-top" alt="">
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-start" style="padding-top: 30px">
                                <p class="mb-0" style="font-size: 0.80rem"><strong>Order summary</strong></>
                            </div>
                            <div id="orderSummaryItems">
                                <!-- Items will be dynamically added here -->
                            </div>
                            <hr>
                            <div class="d-flex align-items-center justify-content-around">
                                <h6>Total</h6>
                                <h6 id="totalValue">$0.00</h6>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>              
            <div class="modal fade" id="getTicketsMobile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen modal-dialog-centered">
                    <div class="modal-content modal-border">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <a type="button" style="padding-right: 10px;" data-bs-dismiss="modal"><i class="fas fa-times"></i></a>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <h6>{{ $event->title }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ date('j F, Y (h:s a)', strtotime($event->date_time_start)) . ' - ' . date('j F, Y (h:s a)', strtotime($event->date_time_end)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="modal-body" style="padding-top: 60px">
                                    @foreach($tickets as $ticket)
                                        @if ($today < $ticket->date_time_end && $ticket->count_orders < $ticket->quantity)
                                            <div class="row mb-4" style="--bs-gutter-x: -0.5rem;">
                                                <div class="col-7">
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <h6>{{ $ticket->title }}</h6>
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="d-flex align-items-center justify-content-center">                                                
                                                        <button class="btn btn-dark px-3 me-2"
                                                            onclick="decrementTicket(this, {{ $ticket->id }})">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <div class="form-outline" style="margin-bottom: 0.5rem;">
                                                            <input class="form-control ticket-select-mobile" 
                                                                id="ticket-input-{{ $ticket->id }}"
                                                                data-ticket-id="{{ $ticket->id }}" 
                                                                data-ticket-price="{{ $ticket->price }}" 
                                                                data-ticket-title="{{ $ticket->title }}" 
                                                                data-ticket-type="{{ $ticket->type }}"
                                                                min="0" 
                                                                max="{{ min(10, $ticket->quantity - $ticket->count_orders) }}" 
                                                                value="0" 
                                                                type="number" 
                                                                style="-webkit-appearance: none; margin: 0;"
                                                                onchange="updateTotal()"/>
                                                        </div>
                                                        <button class="btn btn-dark px-3 ms-2"
                                                            onclick="incrementTicket(this, {{ $ticket->id }})">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-9">
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <p class="mb-0" style="font-size: 0.80rem"><strong>{{ $ticket->type }}</strong></p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <p class="mb-0" style="font-size: 0.80rem">Sales end on {{ date('j F, Y (h:s a)', strtotime($ticket->date_time_end)) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <h6>
                                                            @if ($ticket->type == 'free')
                                                                Free
                                                            @else
                                                                ${{ number_format($ticket->price, 2) }}
                                                            @endif
                                                        </h6>
                                                    </div>
                                                    @if (($ticket->quantity - $ticket->count_orders) <= 10)       
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <p class="mb-0 text-danger" style="font-size: 0.80rem">Only {{ $ticket->quantity - $ticket->count_orders }} left</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <hr>
                                <nav class="fixed-bottom navbar-dark" id="getTicketsBottom">    
                                    <div class="row" style="padding-top: 20px;">
                                        <div class="col-6">
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center justify-content-around">
                                                <h6>Total</h6>
                                                <h6 id="totalValueMobile">${{ number_format($ticket->price, 2) }}</h6>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-grid gap-2" style="padding-left: 20px; padding-right:20px;">
                                                <button type="button" class="btn btn-dark btn-lg" data-bs-target="#checkoutMobile" onclick="if(validateTicketSelection(event)) { $('#getTicketsMobile').modal('hide'); $('#checkoutMobile').modal('show'); }">
                                                    Checkout
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>              
            <!-- Modal Checkout-->
            <div class="modal fade" id="checkout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content modal-border" data-tor="show(p):reveal(up)">
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-center" style="padding-top: 15px">
                                        <h6>Checkout</h6>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="mb-0" style="font-size: 0.80rem">{{ date('j F, Y (h:s a)', strtotime($event->date_time_start)) . ' - ' . date('j F, Y (h:s a)', strtotime($event->date_time_end)) }}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <form id="checkoutForm" onsubmit="return handleCheckout(event)" role="form" method="POST" action="" enctype="multipart/form-data">
                                @csrf
                                <div id="selectedTicketsData"></div>
                                <div class="modal-body" style="padding-top: 10px">
                                    <div class="row">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <h5>Contact Information</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="form-control-label">First Name</label>
                                                <input class="form-control" type="text" name="name_buyer" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_name" class="form-control-label">Last Name</label>
                                                <input class="form-control" type="text" name="last_name_buyer" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="form-control-label">Email</label>
                                                <input class="form-control" type="text" name="email_buyer" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone" class="form-control-label">Phone</label>
                                                <input class="form-control" type="text" id="phone_buyer" name="phone_buyer" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="checkAccept" required>
                                                @if (env('APP_URL') == 'https://tickets.elaftersocialclub.com')
                                                <label class="form-check-label" for="checkAccept">
                                                    I agree to <a class="" target="_blank" href="https://elaftersocialclub.com/terms-and-conditions">tems and conditions.</a>
                                                </label>
                                                @endif
                                              </div>
                                        </div>
                                        <input type="text" hidden name="quantity" id="quantity" value="1">
                                        <input type="text" hidden name="ticket_id" id="ticket_id" value="{{ $ticket->id }}">
                                    </div>
                                </div>
                                <div class="modal-footer" style="padding-top: 50px">
                                <button type="submit" class="btn btn-dark">Place Order</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-4">
                            <div class="d-flex align-items-center justify-content-end">
                                <a type="button" style="padding-right: 5px;" data-bs-dismiss="modal"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="card" style="max-height: 200px">
                                <img src="{{ asset('storage/' .  $event->image) }}" style="height: 100%; object-fit: cover; object-position: top;" class="card-img-bottom" alt="">
                            </div>
                            <div class="d-flex align-items-center justify-content-start" style="padding-top: 30px">
                                <p class="mb-0" style="font-size: 0.80rem"><strong>Order summary</strong></>
                            </div>
                            <div id="orderSummaryItems2">
                                <!-- Items will be dynamically added here -->
                            </div>
                            <hr>
                            <div class="d-flex align-items-center justify-content-around">
                                <h6>Total</h6>
                                <h6 id="totalValue2">$0.00</h6>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="modal fade" id="checkoutMobile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen modal-dialog-centered">
                <div class="modal-content modal-border" data-tor="show(p):reveal(up)">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <a type="button" style="padding-right: 10px;" data-bs-dismiss="modal"><i class="fas fa-times"></i></a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <h6>Checkout</h6>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="mb-0" style="font-size: 0.80rem">{{ date('j F, Y (h:s a)', strtotime($event->date_time_start)) . ' - ' . date('j F, Y (h:s a)', strtotime($event->date_time_end)) }}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <form id="checkoutFormMobile" onsubmit="return handleCheckout(event)" role="form" method="POST" action="" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body" style="padding-top: 10px">
                                    <div class="row">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <h5>Contact Information</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="name" class="form-control-label">First Name</label>
                                                <input class="form-control" type="text" name="name_buyer" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="last_name" class="form-control-label">Last Name</label>
                                                <input class="form-control" type="text" name="last_name_buyer" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="form-control-label">Email</label>
                                                <input class="form-control" type="text" name="email_buyer" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone" class="form-control-label">Phone</label>
                                                <input class="form-control" type="text" id="phone_buyerMobile" name="phone_buyer" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="checkAccept" required>
                                                @if (env('APP_URL') == 'https://tickets.elaftersocialclub.com')
                                                <label class="form-check-label" for="checkAccept">
                                                    I agree to <a class="" target="_blank" href="https://elaftersocialclub.com/terms-and-conditions">tems and conditions.</a>
                                                </label>
                                                @endif
                                              </div>
                                        </div>
                                        <input type="text" hidden name="quantity" id="quantityMobile" value="1">
                                        <input type="text" hidden name="ticket_id" id="ticket_idMobile" value="{{ $ticket->id }}">
                                    </div>
                                </div>
                                <nav class="fixed-bottom navbar-dark" id="getTicketsBottom">  
                                    <div class="row" style="padding-top: 20px;">
                                        <div class="col-6">
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center justify-content-around">
                                                <h6>Total</h6>
                                                <h6 id="totalValueMobile2">${{ number_format($ticket->price, 2) }}</h6>
                                            </div>  
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-grid gap-2" style="padding-left: 20px; padding-right:20px;">
                                                <button type="submit" class="btn btn-dark btn-lg">
                                                    Place Order
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            @endif
        </div>
        <nav class="fixed-bottom navbar-dark" id="getTicketsBottom1">
            @if ($ticket !=null)   
                @if ($today < $ticket->date_time_end) 
                    @if ($count_orders < $ticket->quantity)
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-12 d-flex align-items-center justify-content-center">
                                @if ($ticket->type == 'free')
                                <h4>Free</h4>
                                @endif
                                @if ($ticket->type == 'paid')
                                <h4>$ {{ $ticket->price }}</h4>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-grid gap-2" style="padding-left: 20px; padding-right:20px;">
                                    <button type="button" class="btn btn-yellow btn-lg" data-bs-toggle="modal" data-bs-target="#getTicketsMobile">
                                        Get Tickets
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-12 d-flex align-items-center justify-content-center">
                                <h4>Sales Ended</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-grid gap-2" style="padding-left: 20px; padding-right:20px;">
                                    <button type="button" class="btn btn-yellow btn-lg" data-bs-toggle="modal" data-bs-target="#">
                                        Get Details
                                    </button>
                                </div>
                            </div>
                        </div> 
                    @endif     
                @else
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <h4>Sales Ended</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2" style="padding-left: 20px; padding-right:20px;">
                                <button type="button" class="btn btn-yellow btn-lg" data-bs-toggle="modal" data-bs-target="#">
                                    Get Details
                                </button>
                            </div>
                        </div>
                    </div>       
                @endif 
            @else
                <div class="row pt-5">
                    <div class="col-12">
                        <div class="d-grid gap-2" style="padding-left: 20px; padding-right:20px;">
                            <a type="button" class="btn btn-yellow btn-lg" href="{{ $event->link_external_sales }}" target="_blank">
                                Get Tickets
                            </a>
                        </div>
                    </div>
                </div>    
            @endif
        </nav>
    </main>
    @include('layouts.footers.guest.footer')
@endsection
@push('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@if ($ticket != null)   
<script>
    function validateTicketSelection(event) {
        const isMobile = event.target.closest('[data-bs-target="#checkoutMobile"]') !== null;
        const ticketSelects = isMobile ? document.querySelectorAll('.ticket-select-mobile') : document.querySelectorAll('.ticket-select');
        let hasSelectedTickets = false;
        
        ticketSelects.forEach(select => {
            if (parseInt(select.value) > 0) {
                hasSelectedTickets = true;
            }
        });
        
        if (!hasSelectedTickets) {
            event.preventDefault();
            event.stopPropagation();
            alert('Please select at least one ticket');
            return false;
        }
        return true;
}
    function handleCheckout(event) {
        event.preventDefault();
        const isMobile = event.target.id === 'checkoutFormMobile';
        const ticketSelects = isMobile ? document.querySelectorAll('.ticket-select-mobile') : document.querySelectorAll('.ticket-select');
        const selectedTickets = [];
        const csrfToken = document.querySelector('input[name="_token"]').value;
        const checkoutForm = event.target;
        let hasPaidTicket = false;
        
        ticketSelects.forEach(select => {
            const quantity = parseInt(select.value);
            if (quantity > 0) {
                const ticketType = select.dataset.ticketType;
                if (ticketType === 'paid') {
                    hasPaidTicket = true;
                }
                selectedTickets.push({
                    ticket_id: select.dataset.ticketId,
                    quantity: quantity,
                    price: parseFloat(select.dataset.ticketPrice),
                    type: select.dataset.ticketType
                });
            }
        });

        checkoutForm.action = hasPaidTicket ? '{{ route("stripe.checkout") }}' : '{{ route("order.store") }}';
        // Guardar en sesión vía AJAX
        fetch('/save-tickets-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ tickets: selectedTickets })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                checkoutForm.submit();
            }
        });

        return false;
    }
    // Manejo de versión móvil
    function incrementTicket(button, ticketId) {
        const input = document.getElementById(`ticket-input-${ticketId}`);
        const currentValue = parseInt(input.value);
        const maxValue = parseInt(input.max);
        
        if (currentValue < maxValue) {
            input.value = currentValue + 1;
            input.dispatchEvent(new Event('change'));
        }
    }

    function decrementTicket(button, ticketId) {
        const input = document.getElementById(`ticket-input-${ticketId}`);
        const currentValue = parseInt(input.value);
        const minValue = parseInt(input.min);
        
        if (currentValue > minValue) {
            input.value = currentValue - 1;
            input.dispatchEvent(new Event('change'));
        }
    }

    function updateTotal() {
        let total = 0;
        const inputs = document.querySelectorAll('.ticket-select-mobile');
        inputs.forEach(input => {
            const price = parseFloat(input.dataset.ticketPrice);
            const quantity = parseInt(input.value);
            total += price * quantity;
        });
        
        document.getElementById('totalValueMobile').textContent = `$${total.toFixed(2)}`;
        document.getElementById('totalValueMobile2').textContent = `$${total.toFixed(2)}`;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const ticketSelects = document.querySelectorAll('.ticket-select');
        const ticketSelectsMobile = document.querySelectorAll('.ticket-select-mobile');
        const orderSummaryItems = document.getElementById('orderSummaryItems');
        const orderSummaryItems2 = document.getElementById('orderSummaryItems2');
        const checkoutButton = document.getElementById('checkoutButton');
        const getTicketsModal = document.getElementById('getTickets');
        const getTicketsMobileModal = document.getElementById('getTicketsMobile');
        
        getTicketsModal.addEventListener('show.bs.modal', function () {
            ticketSelects.forEach(select => {
                select.value = "0";
            });
            updateOrderSummary();
        });

        getTicketsMobileModal.addEventListener('show.bs.modal', function () {
            ticketSelectsMobile.forEach(select => {
                select.value = "0";
            });
            updateTotal();
        });

        ticketSelects.forEach(select => {
            select.addEventListener('change', updateOrderSummary);
        });

        function updateOrderSummary() {
            let total = 0;
            let hasTickets = false;
            orderSummaryItems.innerHTML = '';
            orderSummaryItems2.innerHTML = '';
            let selectedTicketsHtml = '';

            ticketSelects.forEach(select => {
                const quantity = parseInt(select.value);
                if (quantity > 0) {
                    hasTickets = true;
                    const price = parseFloat(select.dataset.ticketPrice);
                    const title = select.dataset.ticketTitle;
                    const itemTotal = quantity * price;
                    total += itemTotal;

                    selectedTicketsHtml += `
                        <div class="d-flex align-items-center justify-content-around" style="padding-top: 10px">
                            <p class="mb-0" style="font-size: 0.80rem">${quantity} x ${title}</p>
                            <p class="mb-0" style="font-size: 0.80rem">$${itemTotal.toFixed(2)}</p>
                        </div>`;
                }
            });

            orderSummaryItems.innerHTML = selectedTicketsHtml;
            orderSummaryItems2.innerHTML = selectedTicketsHtml;
            document.getElementById('totalValue').textContent = '$' + total.toFixed(2);
            document.getElementById('totalValue2').textContent = '$' + total.toFixed(2);
            checkoutButton.disabled = !hasTickets;

            // Actualizar formulario de checkout
            updateCheckoutForm();
        }

        function updateCheckoutForm() {
            const selectedTicketsData = document.getElementById('selectedTicketsData');
            if (!selectedTicketsData) return;

            selectedTicketsData.innerHTML = '';
            ticketSelects.forEach(select => {
                const quantity = parseInt(select.value);
                if (quantity > 0) {
                    const ticketId = select.dataset.ticketId;
                    const input = `<input type="hidden" name="tickets[${ticketId}]" value="${quantity}">`;
                    selectedTicketsData.innerHTML += input;
                }
            });
        }

        // Función auxiliar para formatear números
        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        // Máscaras para teléfonos
        const phoneElements = ['phone_buyer', 'phone_buyerMobile'];
        phoneElements.forEach(elementId => {
            const element = document.getElementById(elementId);
            if (element) {
                IMask(element, {
                    mask: [
                        {
                            mask: '+{1}(000)000-0000',
                            startsWith: '1',
                            lazy: true,
                            country: 'Usa'
                        },
                        {
                            mask: '+{52}(000)000-0000',
                            startsWith: '52',
                            lazy: true,
                            country: 'Mexico'
                        }
                    ]
                });
            }
        });

        // Manejo de pestañas
        const tabPairs = [
            ['aInfo', 'aDetails', 'aOrganizer'],
            ['aInfo2', 'aDetails2', 'aOrganizer2']
        ];

        tabPairs.forEach(tabSet => {
            tabSet.forEach(tabId => {
                $(`#${tabId}`).click(function() {
                    tabSet.forEach(id => {
                        $(`#${id}`).css('border-bottom', id === tabId ? 'solid #D9BC73' : 'none');
                    });
                });
            });
        });
    });
</script>
@endif
@endpush
