@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Create Sms Campaign'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            Create Sms Campaign
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm"></p>
                    </div>
                </div> 
            </div>
            <br>
            <form role="form" method="POST" action="{{ route('addcampaign.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title" class="form-control-label">Campaign Title</label>
                            <input class="form-control" type="text" name="title" required>
                        <div class="form-group">
                            <label for="contact_list" class="form-control-label">Send campaign to:</label>
                            <select name="contact_list" class="form-control" id="contact_list">
                                <option value="">Select a contact list...</option>
                                @foreach ($contact_lists as $list)
                                <option value="{{ $list['id'] }}">{{ $list['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message" class="form-control-label">Message</label>
                            <textarea  class="form-control" name="message" id="message" cols="30" rows="5" required></textarea>
                        </div>
                        {{-- <div class="form-group">
                            <label for="schedule" class="form-control-label">Schedule</label>
                            <input class="form-control" type="text" name="schedule" id="schedule" required>
                        </div> --}}
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
<script>
    flatpickr("#schedule", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "F j, Y (h:S K)",
    });
</script>
@endpush

