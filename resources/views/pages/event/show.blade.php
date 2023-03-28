@extends('layouts.app')

@section('content')
    @include('layouts.navbars.guest.navbar')
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-60 pt-5 pb-11 m-6 border-radius-lg"
            style="background-image: url('{{ asset('storage/' .  $event->image) }}'); background-position: top;">
            <span class="mask bg-gradient-dark opacity-3"></span>
        </div>
        <div class="card shadow-lg mt-0">
            <div class="card-body p-3">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">{{ date('j F, Y', strtotime($event->date_time_start)) }}</h5>
                    </div>
                    <div class="d-flex align-items-center">
                        <h3 class="mb-0">{{ $event->title }}</h3>
                    </div>
                    <div class="d-flex align-items-center" style="padding-top: 20px">
                        <h6 class="mb-0">{{ $event->summary }}</h6>
                    </div>
                </div>     
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <i class="far fa-calendar" style="padding-right: 10px"></i>
                                            <h6 class="mb-0">Date and Time</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ date('j F, Y (h:s a)', strtotime($event->date_time_start)) . ' - ' . date('j F, Y (h:s a)', strtotime($event->date_time_end)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card-transparent">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <i class="fas fa-map-pin" style="padding-right: 10px"></i>
                                            <h6 class="mb-0">Location</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0" style="font-size: 0.80rem">{{ $event->ubication }}</p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ $event->maps_url }}" target="_blank"><i class="fas fa-map" style="font-weight: 100">Google Maps</i></a>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="col-6 d-flex align-items-center justify-content-end">
                            <div class="card" style="width: 20rem">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center">
                                        @if ($event->tickets[0]->type == 'free')
                                        <h4>Free</h4>
                                        @endif
                                        @if ($event->tickets[0]->type == 'paid')
                                        <h4>${{ $event->tickets[0]->price }}</h4>
                                        @endif
                                    </div>
                                    <div class="d-grid gap-2" style="padding-top: 10px">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#getTickets">
                                            Get Tickets
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row" style="padding-top: 40px">
                        <div class="card-transparent" style="width: 40rem">
                            <div class="card-header">
                                <h4>About this event</h4>
                            </div>
                            <img src="{{ asset('storage/' .  $event->image) }}" class="card-img-bottom" alt="">
                        </div>
                    </div>
                    <div class="row" style="padding-top: 40px">
                        <div class="card-transparent" style="width: 40rem">
                            <div class="card-header">
                                <h4>Organizer</h4>
                            </div>
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    Contact
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
            <!-- Modal Contact -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
            <!-- Modal GetTickets-->
            <div class="modal fade" id="getTickets" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
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
                                            <h6>{{ $event->tickets[0]->title }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start">
                                            <p class="mb-0" style="font-size: 0.80rem"><strong>{{ $event->tickets[0]->type }}</strong></>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start">
                                            <p class="mb-0" style="font-size: 0.80rem">Sales end on {{ date('j F, Y (h:s a)', strtotime($event->tickets[0]->date_time_end)) }}</p>
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
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="padding-top: 50px">
                            <button type="button" class="btn btn-primary">Register</button>
                            </div>
                        </div>
                        <div class="col-4">
                            <a type="button"  data-bs-dismiss="modal"><i class="fas fa-times"></i></a>
                                <div class="card" style="max-height: 200px">
                                    <img src="{{ asset('storage/' .  $event->image) }}" style="height: 100%; object-fit: cover; object-position: top;" class="card-img-bottom" alt="">
                                </div>
                                <div class="d-flex align-items-center justify-content-start" style="padding-top: 30px">
                                    <p class="mb-0" style="font-size: 0.80rem"><strong>Order summary</strong></>
                                </div>
                                <div class="d-flex align-items-center justify-content-around" style="padding-top: 10px">
                                    <p class="mb-0" style="font-size: 0.80rem" id="totalTickets">1 x {{ $event->tickets[0]->title }}</>
                                        @if ($event->tickets[0]->type == 'free')
                                        <p class="mb-0" style="font-size: 0.80rem">$0.00</>
                                        @else
                                        <p class="mb-0" style="font-size: 0.80rem">${{  number_format($event->tickets[0]->price, 2) }}</>
                                        @endif
                                </div>
                                <hr>
                                <div class="d-flex align-items-center justify-content-around">
                                    <h6>Total</h6>
                                    <h6 id="totalValue">${{ number_format($event->tickets[0]->price, 2) }}</h6>
                                </div>
                            
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </main>
    @include('layouts.footers.guest.footer')
@endsection
@push('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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

    var ticket_name = "{{ $event->tickets[0]->title }}";
    var ticket_value = "{{ $event->tickets[0]->price }}";
    
    $('#selectTickets').change(function() {
        var tickets = $(this).val();
        $("#totalTickets").text(tickets + ' x ' + ticket_name );
        var total_value = (tickets * ticket_value).toFixed(2);
        $("#totalValue").text('$' + total_value);
    });
</script>
@endpush
