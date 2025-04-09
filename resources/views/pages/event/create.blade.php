@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Create Event'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            Create Event
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm"></p>
                    </div>
                </div> 
            </div>
            <br>
            <form role="form" method="POST" action="{{ route('event.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title" class="form-control-label">Event Title</label>
                            <input class="form-control" type="text" name="title" required value="{{ old('title') }}">
                        </div>
                        <div class="form-group">
                            <label for="summary" class="form-control-label">Event Description</label>
                            <textarea  class="form-control" name="summary" id="summary" cols="30" rows="5" required>{{ old('summary') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="location" class="form-control-label">Place</label>
                            <input class="form-control" type="text" name="ubication" required value="{{ old('ubication') }}">
                        </div>
                        <div class="form-group">
                            <label for="street_address" class="form-control-label">Street Address</label>
                            <input class="form-control" type="text" name="street_address" required value="{{ old('street_address') }}"> 
                        </div>
                        <div class="form-group">
                            <label for="address_locality" class="form-control-label">City</label>
                            <input class="form-control" type="text" name="address_locality" required value="{{ old('address_locality') }}">
                        </div>
                        <div class="form-group">
                            <label for="postal_code" class="form-control-label">Postal Code</label>
                            <input class="form-control" type="text" name="postal_code" required value="{{ old('postal_code') }}">
                        </div>
                        <div class="form-group">
                            <label for="address_region" class="form-control-label">State</label>
                            <input class="form-control" type="text" name="address_region" required value="{{ old('address_region') }}">
                        </div>
                        <div class="form-group">
                            <label for="address_country" class="form-control-label">Adress Country</label>
                            <input class="form-control" type="text" name="address_country" required value="{{ old('address_country') }}">
                        </div>
                        <div class="form-group">
                            <label for="maps_url" class="form-control-label">Maps URL</label>
                            <input class="form-control" type="text" name="maps_url" required value="{{ old('maps_url') }}">
                        </div>
                        <div class="form-group">
                            <label for="dateTimeStart" class="form-control-label">Date and Time Start</label>
                            <input class="form-control @error('date_time_start') is-invalid @enderror" type="text" name="date_time_start" id="dateTime" required>
                            @error('date_time_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="dateTimeEnd" class="form-control-label">Date and Time End</label>
                            <input class="form-control @error('date_time_end') is-invalid @enderror" type="text" name="date_time_end" id="dateTimeEnd" required>
                            @error('date_time_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image" class="form-control-label">Cover Image</label>
                            <input class="form-control" type="file" name="coverimage" id="coverimage" required value="{{ old('coverimage') }}">
                        </div>
                        <div class="form-group">
                            <label for="image" class="form-control-label">Main Event Image</label>
                            <input class="form-control" type="file" name="image" id="image" required value="{{ old('image') }}">
                        </div>
                        <div class="form-group">
                            <label for="about" class="form-control-label">About Event</label>
                            <textarea class="form-control" name="about" id="txtDescripcion" cols="30" rows="5">{{ old('about') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="meta_title" class="form-control-label">Meta Title</label>
                            <input class="form-control" type="text" name="meta_title" value="{{ old('meta_title') }}">
                        </div>
                        <div class="form-group">
                            <label for="meta_description" class="form-control-label">Meta Description</label>
                            <input class="form-control" type="text" name="meta_description" value="{{ old('meta_description') }}">
                        </div>
                        <div class="form-check form-switch" style="padding-bottom: 15px;">
                            <input class="form-check-input" type="checkbox" role="switch" id="external_sales" name="external_sales" onchange="toggleExternalSales()" value="{{ old('external_sales') }}">
                            <label class="form-check-label" for="external_sales">External sales</label>
                        </div>
                        <div class="form-group" id="link_external_sales_group" style="display: none;">
                            <label for="meta_description" class="form-control-label">Link external page for sales</label>
                            <input class="form-control" type="text" name="link_external_sales" value="{{ old('link_external_sales') }}">
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
<script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
<script>
    flatpickr("#dateTime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "F j, Y (h:S K)",
    });
    flatpickr("#dateTimeEnd", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "F j, Y (h:S K)",
    });
    ClassicEditor
    .create( document.querySelector( '#txtDescripcion' ) )
    .catch( error => {
    console.error( error );
    } );

    function toggleExternalSales() {
        var externalSalesCheckbox = document.getElementById('external_sales');
        var linkExternalSalesGroup = document.getElementById('link_external_sales_group');
        if (externalSalesCheckbox.checked) {
            linkExternalSalesGroup.style.display = 'block';
        } else {
            linkExternalSalesGroup.style.display = 'none';
        }
    }
</script>
@endpush

