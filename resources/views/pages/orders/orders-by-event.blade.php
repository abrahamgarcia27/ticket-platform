@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Orders'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div id="alert">
            @include('components.alert')
        </div>
        <div class="card-body p-3">
            <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0">Orders of Event {{ $event->title }}</h4>
                </div>
            </div>
            <div class="card-body px-0 pt-4 pb-2">
                <div class="table-responsive p-0">
                    <table class="align-items-center justify-content-center mb-0" id="orders">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phone</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ticket</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ticket type</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Code</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($event->orders as $order)    
                            <tr>
                                <td style="padding-left: 24px">
                                    <p class="text-sm font-weight-bold mb-0">{{ $order->name_buyer . ' ' . $order->last_name_buyer }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $order->email_buyer }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $order->phone_buyer }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $order->ticket->title }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $order->stripe_data == null ? 'Free' : 'Paid' }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $order->ticket->price }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $order->code }}</p>
                                </td>
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

<script>
     
$(document).ready(function () {
    $('#orders').DataTable({
      "paging": true,
      "lengthMenu": [20],
      "scrollY": 400,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": true,
      "responsive": true,
      "orderCellsTop": false,
      "fixedHeader": false,
     
      dom: 'Bfrtip',
      buttons: [{
        extend: 'excel',
        text: '<i class="far fa-file-excel"></i>',
        titleAttr: 'Exportar a excel',
        className: 'btn btn-success'
      },
      {
        extend: 'pdf',
        text: '<i class="far fa-file-pdf"></i>',
        titleAttr: 'Exportar a pdf',
        className: 'btn btn-danger'
      }],
    });
});

</script>
@endpush