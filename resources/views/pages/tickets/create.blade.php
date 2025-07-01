@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Create Tickets'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            Create Tickets for Event {{ $event->title }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm"></p>
                    </div>
                </div> 
            </div>
            <br>
            <div id="alert">
                @include('components.alert')
            </div>
            <form role="form" method="POST" action="{{ route('ticket.store', [$event->id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title" class="form-control-label">Name Ticket</label>
                            <input class="form-control" type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity" class="form-control-label">Available Quantity</label>
                            <input class="form-control" type="number" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="dateTimeStart" class="form-control-label">Sales Date and Time Start</label>
                            <input class="form-control dateTime" type="text" name="date_time_start" required>
                        </div>
                        <div class="form-group">
                            <label for="dateTimeEnd" class="form-control-label">Sales Date and Time End</label>
                            <input class="form-control dateTime" type="text" name="date_time_end" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="paid" value="paid" checked>
                            <label class="form-check-label" for="paid">
                            Paid
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" value="free" id="free">
                            <label class="form-check-label" for="free">
                            Free
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="price" class="form-control-label">Price</label>
                            <input class="form-control" type="text" name="price" id="price" placeholder="$" required>
                        </div>
                        <div class="form-check form-switch" id="feeSwitchGroup" style="padding-bottom: 15px;">
                            <input class="form-check-input" type="checkbox" role="switch" id="has_fee" name="has_fee">
                            <label class="form-check-label" for="has_fee">Do you want to add fees?</label>
                        </div>
                        <div id="feesContainer" style="display: none;">
                            <div class="fee-row mb-3">
                                <div class="row">
                                    <div class="col-5">
                                        <label class="form-control-label">Fee Name</label>
                                        <input type="text" name="fees[0][name]" class="form-control fee-name" placeholder="Fee Name" required>
                                    </div>
                                    <div class="col-5">
                                        <label class="form-control-label">Fee Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="fees[0][amount]" class="form-control fee-amount" placeholder="0.00" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-2 d-flex align-items-center justify-content-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-fee" style="display: none; margin-bottom: 0px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mb-3">
                                <button type="button" class="btn btn-primary btn-sm" id="addFee">
                                    <i class="fas fa-plus"></i> Add Another Fee
                                </button>
                            </div>
                        </div>
                        <div class="form-check form-switch" style="padding-bottom: 15px;">
                            <input class="form-check-input" type="checkbox" role="switch" id="available" name="available" checked>
                            <label class="form-check-label" for="available">Available</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
<!-- Datepicker -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    flatpickr(".dateTime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "F j, Y (h:S K)",
    });
    
    
    
    $("#free").on("change", function(){
        var checked = $(this).is(':checked');
        if(checked){
            $("#price").prop({
                disabled: true,
                required: false
            });
            $("#feeSwitchGroup, #feesContainer").hide();
            $("#has_fee").prop('checked', false).trigger('change');
        }
    }); 
    
    $("#paid").on("change", function(){
        var checked = $(this).is(':checked');
        if(checked){
            $("#price").prop({
                disabled: false,
                required: true
            });
            $("#feeSwitchGroup").show();
        }
    });

    let feeIndex = 1;

    $("#has_fee").on("change", function(){
        if($(this).is(':checked')) {
            $("#feesContainer").show();
        } else {
            $("#feesContainer").hide();
        }
    });

    $("#addFee").on('click', function() {
        const newRow = `
            <div class="fee-row mb-3">
                <div class="row">
                    <div class="col-5">
                        <input type="text" name="fees[${feeIndex}][name]" class="form-control fee-name" placeholder="Fee Name" required>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="fees[${feeIndex}][amount]" class="form-control fee-amount" placeholder="0.00" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-center">
                        <button type="button" class="btn btn-danger btn-sm remove-fee">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>`;
        
        $(newRow).insertBefore($(this).parent());
        feeIndex++;
        
        // Show remove buttons if there are multiple fee rows
        if ($('.fee-row').length > 1) {
            $('.remove-fee').show();
        }
    });
    
    // Remove fee row
    $(document).on('click', '.remove-fee', function() {
        $(this).closest('.fee-row').remove();
        
        // Hide remove buttons if only one fee row remains
        if ($('.fee-row').length <= 1) {
            $('.remove-fee').hide();
        }
        
        // Rename remaining inputs to maintain proper array indexing
        $('.fee-row').each(function(index) {
            $(this).find('.fee-name').attr('name', `fees[${index}][name]`);
            $(this).find('.fee-amount').attr('name', `fees[${index}][amount]`);
        });
        
        feeIndex = $('.fee-row').length;
    });
</script>
@endpush

