@extends('layouts.app')

@section('content')
    <main class="main-content  mt-0">

        <div class="card shadow-lg mt-0">
            
          <div class="modal fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="row">
                    <div class="col-8" style="padding-right: 0px">
                        <div class="row" style="padding-top: 40px;">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-start" style="padding-left:40px;">
                                  <i class="fas fa-check-circle" style="color:#36b571"></i><h6 style="padding-top:5px; padding-left:10px">Thanks for your order! #{{ $order->id }}</h6>
                                </div>
                            </div>
                        </div>
                        <hr> 
                        <div class="modal-body" style="padding-top: 20px; padding-left:40px">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-start">
                                        <h6>YOU'RE GOING TO:</h6>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start">
                                        <p class="mb-0" style="font-size: 30px"><strong>{{ $event->title . ' - ' . date('j F, Y', strtotime($event->date_time_start))}}</strong></>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding-top:40px">
                              <div class="col-6">
                                <div class="d-flex align-items-center justify-content-start">
                                  <h6>{{ count($codes)  . ' Tickey sent to:'}}</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-start">
                                  <p>{{ $order->email_buyer }}</p>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="d-flex align-items-center justify-content-start">
                                  <h6>DATE:</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-start">
                                  <p>{{date('j F, Y (h:s a)', strtotime($event->date_time_start)) }}</p>
                                </div>
                              </div>
                            </div>
                            <div class="row" style="padding-bottom: 80px">
                              <div class="col-12">
                                <div class="d-flex align-items-center justify-content-start">
                                  <h6>LOCATION:</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-start">
                                  <p>{{ $event->ubication }}</p>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-between" style="padding-top: 50px">
                        <a href="{{ route('event.show', [$event->id]) }}" type="button" class="btn btn-primary">Back</a>
                        <div class="col-4">
                          <div class="d-flex align-items-center justify-content-around">
                            <a href="{{ $event->user->facebook_url }}"><i class="fab fa-facebook-f"></i></a>
                            <a href="{{ $event->user->instagram_url }}"><i class="fab fa-instagram"></i></a>
                            <a href="{{ $event->user->web_url }}"><i class="fas fa-globe"></i></a>
                        </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-4" style="border-left: 2px;border-left-color: #d7d7d7;border-left-width: 1px;border-left-style: solid; background-color: #dbdbdb;">
                        <div class="d-flex align-items-center justify-content-end">
                            <a href="{{ route('event.show', [$event->id]) }}" type="button" style="padding-right: 5px;"><i class="fas fa-times"></i></a>
                        </div>
                        <div class="card">
                            <div class="image-container">
                                <img src="{{ asset('storage/' .  $event->image) }}" style="height:auto;" class="card-img-top" alt="">
                            </div>
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
  $( document ).ready(function() {
    $('#myModal').modal('toggle')
  });
</script>

@endpush
