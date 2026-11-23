@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Event'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            Edit Event
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm"></p>
                    </div>
                </div> 
            </div>
            <br>
            <div id="alert">
                @include('components.alert')
            </div>
            <form role="form" method="POST" action="{{ route('event.update', [$event->id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title" class="form-control-label">Event Title</label>
                            <input class="form-control" type="text" name="title" value="{{ $event->title }}" required>
                        </div>
                        <div class="form-group">
                            <label for="summary" class="form-control-label">Event Description</label>
                            <textarea  class="form-control" name="summary" id="summary" cols="30" rows="5" required>{{ $event->summary }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="location" class="form-control-label">Place</label>
                            <input class="form-control" type="text" name="ubication" value="{{ $event->ubication }}" required>
                        </div>
                        <div class="form-group">
                            <label for="street_address" class="form-control-label">Street Address</label>
                            <input class="form-control" type="text" name="street_address" value="{{ $event->street_address }}" required>
                        </div>
                        <div class="form-group">
                            <label for="address_locality" class="form-control-label">City</label>
                            <input class="form-control" type="text" name="address_locality" value="{{ $event->address_locality }}" required>
                        </div>
                        <div class="form-group">
                            <label for="postal_code" class="form-control-label">Postal Code</label>
                            <input class="form-control" type="text" name="postal_code" value="{{ $event->postal_code }}" required>
                        </div>
                        <div class="form-group">
                            <label for="address_region" class="form-control-label">State</label>
                            <input class="form-control" type="text" name="address_region" value="{{ $event->address_region }}" required>
                        </div>
                        <div class="form-group">
                            <label for="address_country" class="form-control-label">Adress Country</label>
                            <input class="form-control" type="text" name="address_country" value="{{ $event->address_country }}" required>
                        </div>
                        <div class="form-group">
                            <label for="maps_url" class="form-control-label">Maps URL</label>
                            <input class="form-control" type="text" name="maps_url" value="{{ $event->maps_url }}" required>
                        </div>
                        <div class="form-group">
                            <label for="dateTimeStart" class="form-control-label">Date and Time Start</label>
                            <input class="form-control" type="text" name="date_time_start" id="dateTime" value="{{ $event->date_time_start }}" required>
                        </div>
                        <div class="form-group">
                            <label for="dateTimeEnd" class="form-control-label">Date and Time End</label>
                            <input class="form-control" type="text" name="date_time_end" id="dateTime" value="{{ $event->date_time_end }}" required>
                        </div>
                        <div class="form-group">
                            <label for="coverimage" class="form-control-label">Cover Event Image</label>
                            <input class="form-control" type="file" name="coverimage" id="coverimage">
                        </div>
                        @if ($event->coverimage)
                        <div class="page-header align-items-start min-vh-25 pt-4 pb-4 m-2 border-radius-lg"
                            style="background-image: url('{{ asset('storage/' .  $event->coverimage) }}'); background-position: center; background-size: cover; height: 180px;">
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="cover_video" class="form-control-label">Cover Event Video (opcional - MP4 / WebM)</label>
                            <input class="form-control" type="file" name="cover_video" id="cover_video" accept="video/*">
                            @if ($event->cover_video)
                                <div class="mt-2">
                                    <video src="{{ asset('storage/' . $event->cover_video) }}" controls style="max-width: 100%; max-height: 200px; border-radius: 8px;"></video>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="image" class="form-control-label">Main Event Image</label>
                            <input class="form-control" type="file" name="image" id="image">
                        </div>
                        @if ($event->image)
                        <div class="page-header align-items-start min-vh-25 pt-4 pb-4 m-2 border-radius-lg"
                            style="background-image: url('{{ asset('storage/' .  $event->image) }}'); background-position: center; background-size: cover; height: 180px;">
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="gallery" class="form-control-label">Event Gallery (Imágenes o Videos para carrusel)</label>
                            <input class="form-control" type="file" name="gallery[]" id="gallery" multiple accept="image/*,video/*">
                            <small class="form-text text-muted">Puedes subir múltiples imágenes o videos para la galería del evento.</small>
                            @if ($event->gallery && is_array($event->gallery))
                                <div class="row mt-2 g-2">
                                    @foreach($event->gallery as $item)
                                        @if(is_string($item) && !empty($item))
                                            <div class="col-3">
                                                @if(preg_match('/\.(mp4|webm|ogg|mov)$/i', $item))
                                                    <video src="{{ asset('storage/' . $item) }}" controls style="width: 100%; height: 80px; object-fit: cover; border-radius: 6px;"></video>
                                                @else
                                                    <img src="{{ asset('storage/' . $item) }}" style="width: 100%; height: 80px; object-fit: cover; border-radius: 6px;">
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="about" class="form-control-label">About Event</label>
                            <textarea class="form-control" name="about" id="txtDescripcion" cols="30" rows="5">{!! $event->about !!}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="meta_title" class="form-control-label">Meta Title</label>
                            <input class="form-control" type="text" name="meta_title" value="{{ $event->meta_title }}">
                        </div>
                        <div class="form-group">
                            <label for="meta_description" class="form-control-label">Meta Description</label>
                            <input class="form-control" type="text" name="meta_description" value="{{ $event->meta_description }}">
                        </div>
                        <div class="form-check form-switch" style="padding-bottom: 15px;">
                            <input class="form-check-input" type="checkbox" role="switch" id="external_sales" name="external_sales" onchange="toggleExternalSales()" {{ $event->link_external_sales !== null ? 'checked' : '' }}>
                            <label class="form-check-label" for="external_sales">External sales</label>
                        </div>
                        <div class="form-group" id="link_external_sales_group" style="{{ $event->link_external_sales !== null ? '' : 'display: none;' }}">
                            <label for="meta_description" class="form-control-label">Link external page for sales</label>
                            <input class="form-control" type="text" name="link_external_sales" value="{{ $event->link_external_sales }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm ms-auto">Update</button>
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

