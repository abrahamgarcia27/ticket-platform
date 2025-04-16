<div class="px-4 pt-4">
    @if ($message = session()->has('succes'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p class="text-white mb-0">{{ session()->get('succes') }}</p>
        </div>
    @endif
    @if ($message = session()->has('error'))
        <div class="alert" role="alert" style="background-color: #000 !important;">
            <p class="text-white mb-0">{{ session()->get('error') }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert" role="alert" style="background-color: #000 !important;">
            @foreach ($errors->all() as $error)
                <p class="text-white mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif
</div>
