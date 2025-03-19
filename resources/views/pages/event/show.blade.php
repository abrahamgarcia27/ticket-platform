@extends('layouts.app')

@section('content')
    <main class="main-content mt-0">
        {{-- <div id="alert">
            @include('components.alert')
        </div> --}}
        <div class="header">
            <a href="/list-events">
                <img src="{{ asset('img/logos/logo.png') }}" alt="Logo" class="logo">
            </a>
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
                                <div class="col-sm-6">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <i class="far fa-calendar" style="padding-right: 10px"></i>
                                            <h6 class="mb-0">Doors Open At</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ date('j F, Y (h:s a)', strtotime($event->date_time_start)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
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
                                <div class="col-sm-6">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <i class="far fa-calendar" style="padding-right: 10px"></i>
                                            <h6 class="mb-0">Doors Open At</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ date('j F, Y (h:s a)', strtotime($event->date_time_start)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 locationDiv">
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
                            @if ($ticket !== null)    
                            <div class="card-transpatent" style="width: 20rem;">
                                @if ($today < $ticket->date_time_end)
                                    @if ($count_orders < $ticket->quantity) 
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center">
                                            @if ($ticket->type == 'free')
                                            <h4>Free</h4>
                                            @endif
                                            @if ($ticket->type == 'paid')
                                            <h4>${{ $ticket->price }}</h4>
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
                                <div class="row">
                                    <div class="col-9">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <h6>{{ $ticket->title }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start">
                                            <p class="mb-0" style="font-size: 0.80rem"><strong>{{ $ticket->type }}</strong></>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start">
                                            <p class="mb-0" style="font-size: 0.80rem">Sales end on {{ date('j F, Y (h:s a)', strtotime($ticket->date_time_end)) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <select class="form-select" id="selectTickets" aria-label="Default select example">
                                                <option value="1" selected>1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                              </select>
                                        </div>
                                        @if (($ticket->quantity - $count_orders) <= '10')      
                                        <div class="d-flex align-items-center justify-content-start">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ 'Only ' . $ticket->quantity - $count_orders . ' left'}}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="padding-top: 50px">
                            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#checkout">Checkout</button>
                            </div>
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
                            <div class="d-flex align-items-center justify-content-around" style="padding-top: 10px">
                                <p class="mb-0" style="font-size: 0.80rem" id="totalTickets">1 x {{ $ticket->title }}</>
                                    @if ($ticket->type == 'free')
                                    <p class="mb-0" style="font-size: 0.80rem">$0.00</>
                                    @else
                                    <p class="mb-0" style="font-size: 0.80rem">${{  number_format($ticket->price, 2) }}</>
                                    @endif
                            </div>
                            <hr>
                            <div class="d-flex align-items-center justify-content-around">
                                <h6>Total</h6>
                                <h6 id="totalValue">${{ number_format($ticket->price, 2) }}</h6>
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
                                    <div class="row" style="--bs-gutter-x: -0.5rem;">
                                        <div class="col-7">
                                            <div class="d-flex align-items-center justify-content-start">
                                                <h6>{{ $ticket->title }}</h6>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="d-flex align-items-center justify-content-center">                                                
                                                <button class="btn btn-dark px-3 me-2 moreLess"
                                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                <i class="fas fa-minus"></i>
                                                </button>
                                                <div class="form-outline" style="margin-bottom: 0.5rem;">
                                                <input id="inputTickets" min="1"  max="10" name="quantity" value="1" type="number" class="form-control" style="-webkit-appearance: none;
                                                margin: 0;"/>
                                                </div>
                                                <button class="btn btn-dark px-3 ms-2 moreLess"
                                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="d-flex align-items-center justify-content-start">
                                                <p class="mb-0" style="font-size: 0.80rem"><strong>{{ $ticket->type }}</strong></>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <p class="mb-0" style="font-size: 0.80rem">Sales end on {{ date('j F, Y (h:s a)', strtotime($ticket->date_time_end)) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            @if (($ticket->quantity - $count_orders) <= '10')       
                                            <div class="d-flex align-items-center justify-content-start">
                                                <p class="mb-0" style="font-size: 0.80rem">{{ 'Only ' . $ticket->quantity - $count_orders . ' left'}}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
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
                                                <button type="button" class="btn btn-dark btn-lg" data-bs-toggle="modal" data-bs-target="#checkoutMobile">
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
                            <form role="form" method="POST" action="{{ $ticket->type == 'paid' ? route('stripe.checkout') : route('order.store') }}" enctype="multipart/form-data">
                                @csrf
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
                            <div class="d-flex align-items-center justify-content-around" style="padding-top: 10px">
                                <p class="mb-0" style="font-size: 0.80rem" id="totalTickets2"></>
                                    @if ($ticket->type == 'free')
                                    <p class="mb-0" style="font-size: 0.80rem">$0.00</>
                                    @else
                                    <p class="mb-0" style="font-size: 0.80rem">${{  number_format($ticket->price, 2) }}</>
                                    @endif
                            </div>
                            <hr>
                            <div class="d-flex align-items-center justify-content-around">
                                <h6>Total</h6>
                                <h6 id="totalValue2">${{ number_format($ticket->price, 2) }}</h6>
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
                            <form role="form" method="POST" action="{{ $ticket->type == 'paid' ? route('stripe.checkout') : route('order.store') }}" enctype="multipart/form-data">
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
   function addCommas(nStr){
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
    
    var ticket_name = "{{ $ticket->title }}";
    var ticket_value = "{{ $ticket->price }}";
    
    $('#selectTickets').change(function() {
        var tickets = $(this).val();
        $("#totalTickets").text(tickets + ' x ' + ticket_name );
        $("#totalTickets2").text(tickets + ' x ' + ticket_name );
        $("#quantity").val(tickets);
        var total_value = (tickets * ticket_value).toFixed(2);
        $("#totalValue").text('$' + total_value);
        $("#totalValue2").text('$' + total_value);
    });
    $('.moreLess').click(function() {
        var tickets = $('#inputTickets').val();
        var total_value = (tickets * ticket_value).toFixed(2);
        $("#quantityMobile").val(tickets);
        $("#totalValueMobile").text('$' + total_value);
        $("#totalValueMobile2").text('$' + total_value);
       console.log(tickets);
    });
    
    var element = document.getElementById('phone_buyer');  
      var mask = IMask(element, {
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
      },
        ]
        });
    var element = document.getElementById('phone_buyerMobile');  
      var mask = IMask(element, {
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
      },
        ]
        });
        

        $('#aInfo').click(function() {
            $('#aInfo').css('border-bottom', 'solid #D9BC73');
            $('#aDetails').css('border-bottom', 'none');
            $('#aOrganizer').css('border-bottom', 'none');
        });
        $('#aDetails').click(function() {
            $('#aInfo').css('border-bottom', 'none');
            $('#aDetails').css('border-bottom', 'solid #D9BC73');
            $('#aOrganizer').css('border-bottom', 'none');
        });
        $('#aOrganizer').click(function() {
            $('#aInfo').css('border-bottom', 'none');
            $('#aDetails').css('border-bottom', 'none');
            $('#aOrganizer').css('border-bottom', 'solid #D9BC73');
        });
        $('#aInfo2').click(function() {
            $('#aInfo2').css('border-bottom', 'solid #D9BC73');
            $('#aDetails2').css('border-bottom', 'none');
            $('#aOrganizer2').css('border-bottom', 'none');
        });
        $('#aDetails2').click(function() {
            $('#aInfo2').css('border-bottom', 'none');
            $('#aDetails2').css('border-bottom', 'solid #D9BC73');
            $('#aOrganizer2').css('border-bottom', 'none');
        });
        $('#aOrganizer2').click(function() {
            $('#aInfo2').css('border-bottom', 'none');
            $('#aDetails2').css('border-bottom', 'none');
            $('#aOrganizer2').css('border-bottom', 'solid #D9BC73');
        });
        

  
</script>
@endif
@endpush
