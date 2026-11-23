@extends('layouts.app')

@section('content')
<style>
    /* Estilos Fever-like en tema claro y elegante */
    :root {
        --fever-gold: #D9BC73;
        --fever-gold-dark: #b89849;
        --fever-gold-light: #fdfaf2;
        --fever-dark: #111827;
        --fever-gray-600: #4b5563;
        --fever-gray-500: #6b7280;
        --fever-gray-200: #e5e7eb;
        --fever-gray-100: #f9fafb;
    }

    body {
        background-color: #ffffff !important;
        color: #1f2937 !important;
        font-family: 'Open Sans', sans-serif;
    }

    .fever-event-page {
        background-color: #ffffff;
        padding-bottom: 90px;
    }

    /* Top navigation header */
    .fever-nav-header {
        background-color: #0E1012;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fever-nav-header .logo {
        max-height: 40px;
    }

    /* Top Hero Multimedia (Single Video OR Single Image) */
    .fever-hero-media-wrapper {
        background-color: #0E1012;
        width: 100%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        min-height: 340px;
        max-height: 520px;
    }

    .fever-hero-media-content {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fever-hero-video {
        width: 100%;
        max-height: 500px;
        object-fit: contain;
        background-color: #0E1012;
    }

    .fever-hero-image {
        width: 100%;
        max-height: 500px;
        object-fit: contain;
        background-color: #0E1012;
    }

    /* Event Title Header & Key Metadata */
    .fever-event-header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 32px 20px 20px 20px;
        background-color: #ffffff;
    }

    .fever-badge-category {
        display: inline-block;
        background-color: #fef9ed;
        color: var(--fever-gold-dark);
        border: 1px solid var(--fever-gold);
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .fever-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--fever-dark);
        margin-bottom: 16px;
        line-height: 1.2;
    }

    @media (max-width: 768px) {
        .fever-title {
            font-size: 1.85rem;
        }
    }

    .fever-key-points {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        color: var(--fever-gray-600);
        font-size: 0.95rem;
        padding-bottom: 8px;
    }

    .fever-key-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .fever-key-item i {
        color: var(--fever-gold-dark);
        font-size: 1.05rem;
    }

    /* Sticky Navigation Tabs */
    .fever-sticky-tabs {
        position: sticky;
        top: 0;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(8px);
        border-top: 1px solid var(--fever-gray-200);
        border-bottom: 1px solid var(--fever-gray-200);
        z-index: 100;
        padding: 0 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .fever-tabs-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        gap: 32px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .fever-tabs-wrapper::-webkit-scrollbar {
        display: none;
    }

    .fever-tab-link {
        color: var(--fever-gray-500);
        text-decoration: none;
        padding: 16px 0;
        font-weight: 600;
        font-size: 0.95rem;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .fever-tab-link:hover, .fever-tab-link.active {
        color: #000000;
        border-bottom-color: var(--fever-gold-dark);
    }

    /* Content Grid Layout */
    .fever-content-grid {
        max-width: 1200px;
        margin: 32px auto 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 40px;
    }

    @media (max-width: 991px) {
        .fever-content-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Section Cards */
    .fever-section {
        background: #ffffff;
        border: 1px solid var(--fever-gray-200);
        border-radius: 14px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }

    .fever-section-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--fever-dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fever-section-title i {
        color: var(--fever-gold-dark);
    }

    /* Gallery Carousel */
    .fever-gallery-carousel {
        position: relative;
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        background-color: #000;
        height: 420px;
    }

    @media (max-width: 768px) {
        .fever-gallery-carousel {
            height: 280px;
        }
    }

    .fever-gallery-slide {
        display: none;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        transition: opacity 0.4s ease-in-out;
    }

    .fever-gallery-slide.active {
        display: block;
        opacity: 1;
    }

    .fever-gallery-item {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background-color: #0E1012;
    }

    .fever-gallery-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.65);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        transition: all 0.2s;
    }

    .fever-gallery-btn:hover {
        background: var(--fever-gold);
        color: #0E1012;
    }

    .fever-gallery-prev { left: 16px; }
    .fever-gallery-next { right: 16px; }

    .fever-gallery-dots {
        position: absolute;
        bottom: 14px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 5;
    }

    .fever-gdot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s;
    }

    .fever-gdot.active {
        background: var(--fever-gold);
        transform: scale(1.3);
    }

    /* Sticky Sidebar Purchase Box */
    .fever-purchase-box {
        position: sticky;
        top: 80px;
        background: #ffffff;
        border: 1px solid var(--fever-gray-200);
        border-radius: 16px;
        padding: 26px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .fever-price-from {
        font-size: 0.85rem;
        color: var(--fever-gray-500);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .fever-price-val {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--fever-gold-dark);
        margin: 4px 0 16px 0;
    }

    .fever-btn-buy {
        background: var(--fever-gold);
        color: #0E1012;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        padding: 14px 20px;
        width: 100%;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(217, 188, 115, 0.35);
    }

    .fever-btn-buy:hover {
        background: var(--fever-gold-hover);
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(217, 188, 115, 0.45);
    }

    .fever-info-pill {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid var(--fever-gray-200);
        color: var(--fever-dark);
    }
    .fever-info-pill:last-child {
        border-bottom: none;
    }
    .fever-info-pill i {
        font-size: 1.15rem;
        color: var(--fever-gold-dark);
        width: 24px;
        text-align: center;
    }

    /* Embedded Map */
    .fever-map-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--fever-gray-200);
        height: 280px;
        margin-top: 15px;
    }

    /* Mobile Sticky Action Bar */
    .fever-mobile-sticky-action {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        border-top: 1px solid var(--fever-gray-200);
        padding: 12px 20px;
        z-index: 1050;
        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.08);
    }

    @media (max-width: 991px) {
        .fever-mobile-sticky-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    }
</style>

<div class="fever-event-page">
    {{-- Header Logo / Navigation --}}
    <div class="fever-nav-header">
        <a href="/list-events">
            <img src="{{ asset('img/logos/logo.png') }}" alt="Logo" class="logo">
        </a>
        <a href="/list-events" class="btn btn-sm btn-outline-light mb-0">
            <i class="fas fa-arrow-left me-1"></i> All Events
        </a>
    </div>

    <div id="alert">
        @include('components.alert')
    </div>

    {{-- Top of the Event: Single Video or Single Image (No carousel) --}}
    <div class="fever-hero-media-wrapper">
        <div class="fever-hero-media-content">
            @if($event->cover_video)
                <video class="fever-hero-video" controls autoplay muted loop playsinline>
                    <source src="{{ asset('storage/' . $event->cover_video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @elseif($event->coverimage)
                <img src="{{ asset('storage/' . $event->coverimage) }}" class="fever-hero-image" alt="{{ $event->title }}">
            @elseif($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" class="fever-hero-image" alt="{{ $event->title }}">
            @endif
        </div>
    </div>

    {{-- Event Header Details (White Background) --}}
    <div class="fever-event-header-container">
        <span class="fever-badge-category"><i class="fas fa-star me-1"></i> Featured Event</span>
        <h1 class="fever-title">{{ $event->title }}</h1>
        
        <div class="fever-key-points">
            <div class="fever-key-item">
                <i class="far fa-calendar-alt"></i>
                <span>{{ date('F j, Y', strtotime($event->date_time_start)) }}</span>
            </div>
            <div class="fever-key-item">
                <i class="far fa-clock"></i>
                <span>{{ date('g:i A', strtotime($event->date_time_start)) }} - {{ date('g:i A', strtotime($event->date_time_end)) }}</span>
            </div>
            <div class="fever-key-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $event->ubication }} ({{ $event->address_locality }}, {{ $event->address_region }})</span>
            </div>
            @php
                $minPrice = $tickets->where('type', 'paid')->min('price');
                $hasFree = $tickets->where('type', 'free')->isNotEmpty();
            @endphp
            <div class="fever-key-item">
                <i class="fas fa-ticket-alt"></i>
                @if($hasFree && !$minPrice)
                    <span>Free Admission</span>
                @elseif($minPrice)
                    <span>Tickets from ${{ number_format($minPrice, 2) }}</span>
                @else
                    <span>Available Tickets</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Sticky Tabs (Overview, Gallery, Location, Organizer) --}}
    <nav class="fever-sticky-tabs">
        <div class="fever-tabs-wrapper">
            <a href="#overview" class="fever-tab-link active" id="tab-overview">Overview</a>
            <a href="#gallery-section" class="fever-tab-link" id="tab-gallery">Gallery</a>
            <a href="#location" class="fever-tab-link" id="tab-location">Location</a>
            <a href="#organizer" class="fever-tab-link" id="tab-organizer">Organizer</a>
        </div>
    </nav>

    {{-- Content Grid --}}
    <div class="fever-content-grid">
        <div class="fever-main-column">
            {{-- Overview --}}
            <section class="fever-section" id="overview">
                <h3 class="fever-section-title"><i class="fas fa-info-circle"></i> About this event</h3>
                
                @if($event->summary)
                    <p class="lead mb-4" style="font-size: 1.1rem; line-height: 1.6; color: #374151;">
                        {{ $event->summary }}
                    </p>
                @endif

                @if($event->about)
                    <div class="about-html" style="color: #4b5563; line-height: 1.7;">
                        {!! $event->about !!}
                    </div>
                @else
                    <p style="color: #6b7280;">Join us for an unforgettable experience at {{ $event->ubication }}. Enjoy live music, curated atmosphere, and premium service.</p>
                @endif
            </section>

            {{-- Gallery Carousel Section (Images or Videos) --}}
            @php
                $galleryItems = [];
                if (!empty($event->gallery) && is_array($event->gallery)) {
                    $galleryItems = $event->gallery;
                } else {
                    if ($event->image) $galleryItems[] = $event->image;
                    if ($event->coverimage) $galleryItems[] = $event->coverimage;
                }
            @endphp
            <section class="fever-section" id="gallery-section">
                <h3 class="fever-section-title"><i class="fas fa-images"></i> Gallery</h3>
                
                @if(!empty($galleryItems))
                    <div class="fever-gallery-carousel" id="eventGalleryCarousel">
                        @foreach($galleryItems as $index => $item)
                            <div class="fever-gallery-slide {{ $index === 0 ? 'active' : '' }}">
                                @if(preg_match('/\.(mp4|webm|ogg|mov)$/i', $item))
                                    <video class="fever-gallery-item" controls playsinline>
                                        <source src="{{ asset('storage/' . $item) }}">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $item) }}" class="fever-gallery-item" alt="Gallery item {{ $index + 1 }}">
                                @endif
                            </div>
                        @endforeach

                        @if(count($galleryItems) > 1)
                            <button class="fever-gallery-btn fever-gallery-prev" onclick="prevGallerySlide()">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="fever-gallery-btn fever-gallery-next" onclick="nextGallerySlide()">
                                <i class="fas fa-chevron-right"></i>
                            </button>

                            <div class="fever-gallery-dots">
                                @foreach($galleryItems as $index => $item)
                                    <div class="fever-gdot {{ $index === 0 ? 'active' : '' }}" onclick="goToGallerySlide({{ $index }})"></div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-muted">No gallery items available yet.</p>
                @endif
            </section>

            {{-- Location & Map --}}
            <section class="fever-section" id="location">
                <h3 class="fever-section-title"><i class="fas fa-map-marked-alt"></i> Location</h3>
                <div class="fever-info-pill">
                    <i class="fas fa-building"></i>
                    <div>
                        <strong style="color: #111827;">{{ $event->ubication }}</strong>
                        <div class="text-sm text-muted">{{ $location }}</div>
                    </div>
                </div>

                <div class="fever-map-container">
                    <iframe
                        width="100%"
                        height="100%"
                        frameborder="0"
                        style="border:0;"
                        src="https://maps.google.com/maps?q={{ urlencode($location) }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="mt-3 text-end">
                    <a href="{{ $event->maps_url ?: 'https://maps.google.com/?q=' . urlencode($location) }}" target="_blank" class="btn btn-sm btn-outline-dark mb-0">
                        <i class="fas fa-directions me-1"></i> Open in Google Maps
                    </a>
                </div>
            </section>

            {{-- Organizer --}}
            <section class="fever-section" id="organizer">
                <h3 class="fever-section-title"><i class="fas fa-user-tie"></i> Organizer</h3>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-lg">
                        <img src="{{ $event->user && $event->user->image ? asset('storage/' . $event->user->image) : asset('img/logos/logo.png') }}" alt="organizer" class="border-radius-lg shadow-sm w-100">
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold" style="color: #111827;">{{ $event->user ? $event->user->username : 'Event Organizer' }}</h5>
                        <p class="text-sm text-muted mb-0">Event Host</p>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <i class="far fa-envelope me-1"></i> Contact Organizer
                    </button>
                </div>
            </section>
        </div>

        {{-- Right Column Sticky Purchase Box --}}
        <div class="fever-sidebar-column d-none d-lg-block">
            <div class="fever-purchase-box">
                <div class="fever-price-from">Tickets starting from</div>
                <div class="fever-price-val">
                    @if($hasFree && !$minPrice)
                        Free
                    @elseif($minPrice)
                        ${{ number_format($minPrice, 2) }}
                    @else
                        --
                    @endif
                </div>

                <div class="my-3">
                    <div class="fever-info-pill">
                        <i class="far fa-calendar"></i>
                        <span class="text-sm" style="color: #374151;">{{ date('M j, Y', strtotime($event->date_time_start)) }}</span>
                    </div>
                    <div class="fever-info-pill">
                        <i class="far fa-clock"></i>
                        <span class="text-sm" style="color: #374151;">{{ date('g:i A', strtotime($event->date_time_start)) }}</span>
                    </div>
                    <div class="fever-info-pill">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="text-sm text-truncate" style="max-width: 250px; color: #374151;">{{ $event->ubication }}</span>
                    </div>
                </div>

                @if ($tickets->isNotEmpty())
                    <button type="button" class="fever-btn-buy" data-bs-toggle="modal" data-bs-target="#getTickets">
                        Get Tickets
                    </button>
                @elseif($event->link_external_sales)
                    <a href="{{ $event->link_external_sales }}" target="_blank" class="fever-btn-buy text-center d-block text-decoration-none">
                        Get Tickets
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Mobile Sticky Action Bar --}}
    <div class="fever-mobile-sticky-action">
        <div>
            <div class="text-xs text-muted">From</div>
            <div class="h5 mb-0 font-weight-bold" style="color: var(--fever-gold-dark);">
                @if($hasFree && !$minPrice)
                    Free
                @elseif($minPrice)
                    ${{ number_format($minPrice, 2) }}
                @else
                    --
                @endif
            </div>
        </div>
        <div>
            @if ($tickets->isNotEmpty())
                <button type="button" class="btn btn-yellow mb-0" data-bs-toggle="modal" data-bs-target="#getTicketsMobile">
                    Get Tickets
                </button>
            @elseif($event->link_external_sales)
                <a href="{{ $event->link_external_sales }}" target="_blank" class="btn btn-yellow mb-0">
                    Get Tickets
                </a>
            @endif
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
                                        <p class="mb-0" style="font-size: 0.80rem">{{ date('F j, Y (h:s a)', strtotime($event->date_time_start)) . ' - ' . date('F j, Y (h:s a)', strtotime($event->date_time_end)) }}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="modal-body" style="padding-top: 60px">
                                @foreach($tickets as $ticket)
                                    @if ($today < $ticket->date_time_end)
                                    <div class="row mb-4">
                                        <div class="col-9 position-relative">
                                            @if($ticket->orders_count >= $ticket->quantity)
                                            <div class="sold-out-overlay">
                                                <img src="{{ asset('img/sold-out.png') }}" alt="Sold Out" class="img-fluid-sold-out">
                                            </div>
                                            @endif
                                            <div class="@if($ticket->orders_count >= $ticket->quantity) opacity-25 @endif">
                                                <div class="d-flex align-items-center justify-content-start">
                                                    <h6>{{ $ticket->title }}</h6>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-start">
                                                    <p class="mb-0" style="font-size: 0.80rem"><strong>{{ $ticket->type }}</strong></p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-start">
                                                    <p class="mb-0" style="font-size: 0.80rem">Sales end on {{ date('F j, Y (h:i a)', strtotime($ticket->date_time_end)) }}</p>
                                                </div>
                                                @if (($ticket->quantity - $ticket->orders_count) <= 10)      
                                                <div class="d-flex align-items-center justify-content-start">
                                                    <p class="mb-0 text-danger" style="font-size: 0.80rem">Only {{ $ticket->quantity - $ticket->orders_count }} left</p>
                                                </div>
                                                @endif
                                            </div>
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
                                                <select class="form-select mb-2 ticket-select" data-ticket-id="{{ $ticket->id }}" data-ticket-price="{{ $ticket->price }}" data-ticket-title="{{ $ticket->title }}" data-ticket-type="{{ $ticket->type }}" @if($ticket->orders_count >= $ticket->quantity) disabled @endif>
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
                            <button type="button" class="btn btn-dark" id="desktopCheckoutBtn">Checkout</button>
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
                                            <p class="mb-0" style="font-size: 0.80rem">{{ date('F j, Y (h:s a)', strtotime($event->date_time_start)) . ' - ' . date('F j, Y (h:s a)', strtotime($event->date_time_end)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="modal-body" style="padding-top: 20px">
                                    <!-- Date and Time Header -->
                                    <div class="text-center mb-3">
                                        <div style="color: #343a40; font-size: 0.9rem; font-weight: 600; margin-bottom: 8px;">
                                            <i class="fas fa-calendar-alt me-2"></i>{{ date('D, M j', strtotime($event->date_time_start)) }}
                                        </div>
                                        <div style="color: #343a40; font-size: 0.9rem; font-weight: 600;">
                                            <i class="fas fa-clock me-2"></i>{{ date('g:i A', strtotime($event->date_time_start)) }}
                                        </div>
                                    </div>

                                    <!-- Tickets Section Header -->
                                    <div class="d-flex align-items-center mb-3" style="color: #343a40; font-size: 1rem; font-weight: 600;">
                                        <i class="fas fa-ticket-alt me-2"></i>Tickets
                                    </div>

                                    @foreach($tickets as $ticket)
                                        @if ($today < $ticket->date_time_end)
                                            <div class="ticket-card-mobile position-relative @if($ticket->orders_count >= $ticket->quantity) opacity-25 @endif">
                                                @if($ticket->orders_count >= $ticket->quantity)
                                                <div class="sold-out-overlay">
                                                    <img src="{{ asset('img/sold-out.png') }}" alt="Sold Out" class="img-fluid-sold-out">
                                                </div>
                                                @endif 
                                                
                                                <!-- Top section - Ticket Type -->
                                                <div class="ticket-type-section">
                                                    <div class="ticket-type-mobile">{{ $ticket->title }}</div>
                                                </div>
                                                
                                                <!-- Bottom section - Event Title and Controls -->
                                                <div class="ticket-controls-section">
                                                    <div class="event-title-mobile">{{ $event->title }}</div>
                                                    
                                                    <div class="ticket-controls-mobile">
                                                        <div class="ticket-info-section">
                                                            <div class="ticket-price-mobile">
                                                                @if ($ticket->type == 'free')
                                                                    Free
                                                                @else
                                                                    ${{ number_format($ticket->price, 2) }}
                                                                @endif
                                                            </div>
                                                            <div class="ticket-sales-end">
                                                                Sales end on {{ date('M j, Y', strtotime($ticket->date_time_end)) }}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="ticket-quantity-controls">
                                                            <button @if($ticket->orders_count >= $ticket->quantity) disabled @endif 
                                                                class="btn-ticket-minus"
                                                                onclick="decrementTicket(this, {{ $ticket->id }})">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input @if($ticket->orders_count >= $ticket->quantity) disabled @endif 
                                                                class="form-control ticket-select-mobile" 
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
                                                            <button @if($ticket->orders_count >= $ticket->quantity) disabled @endif 
                                                                class="btn-ticket-plus"
                                                                onclick="incrementTicket(this, {{ $ticket->id }})">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
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
                                        <p class="mb-0" style="font-size: 0.80rem">{{ date('F j, Y (h:s a)', strtotime($event->date_time_start)) . ' - ' . date('F j, Y (h:s a)', strtotime($event->date_time_end)) }}</p>
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
                                                <label class="form-check-label" for="checkAccept">
                                                    I agree to <a class="" target="_blank" href="{{ env('TERMS_AND_CONDITIONS_URL') }}">terms and conditions.</a>
                                                </label>
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
                                        <p class="mb-0" style="font-size: 0.80rem">{{ date('F j, Y (h:s a)', strtotime($event->date_time_start)) . ' - ' . date('F j, Y (h:s a)', strtotime($event->date_time_end)) }}</p>
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
                                                <label class="form-check-label" for="checkAccept">
                                                    I agree to <a class="" target="_blank" href="{{ env('TERMS_AND_CONDITIONS_URL') }}">terms and conditions.</a>
                                                </label>
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
        {{-- <nav class="fixed-bottom navbar-dark" id="getTicketsBottom1">
            @if ($ticket !=null)   
                @if ($today < $ticket->date_time_end) 
                    @if ($count_orders < $ticket->quantity)
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-12 d-flex align-items-center justify-content-center">
                                @if ($ticket->type == 'free')
                                <h4>Free</h4>
                                @endif
                                @if ($ticket->type == 'paid')
                                <h4></h4>
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
        </nav> --}}
        
        <!-- Mobile Bottom Get Tickets Button (only visible on mobile) -->
        <div class="mobile-bottom-ticket d-lg-none">
            <div class="container-fluid">
                @if ($ticket != null)
                    @php
                        $today = now();
                        $hasAvailableTickets = $tickets->where('date_time_end', '>', $today)->isNotEmpty();
                        $lowestPaidPrice = $tickets->where('type', 'paid')
                            ->where('date_time_end', '>', $today)
                            ->min('price');
                        $hasFreeTickets = $tickets->where('type', 'free')
                            ->where('date_time_end', '>', $today)
                            ->isNotEmpty();
                    @endphp
                    
                    @if ($hasAvailableTickets)
                        {{-- Show "Free" ONLY for free events --}}
                        @if ($hasFreeTickets && !$lowestPaidPrice || $lowestPaidPrice == 0)
                            <div class="d-flex align-items-center justify-content-center" style="padding-bottom: 10px;">
                                <h4 style="margin: 0; color: #333;">Free</h4>
                            </div>
                        @endif
                        
                        <button type="button" class="btn btn-yellow btn-lg w-100" id="mobileGetTicketsBtn">
                            Get Tickets
                        </button>
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="padding-bottom: 10px;">
                            <h4 style="margin: 0; color: #333;">Sales Ended</h4>
                        </div>
                        <button type="button" class="btn btn-yellow btn-lg w-100">
                            Get Details
                        </button>
                    @endif
                @else
                    {{-- External Sales Button for Mobile --}}
                    <a type="button" class="btn btn-yellow btn-lg w-100" href="{{ $event->link_external_sales }}" target="_blank">
                        Get Tickets
                    </a>
                @endif
            </div>
        </div>
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

        // Manejo de pestañas (solo para desktop, no para mobile top nav)
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

        // Mobile top nav scroll behavior
        if (window.innerWidth <= 959) {
            const sections = ['whenandwhere', 'about', 'organizer'];
            const navLinks = ['aMobileInfo', 'aMobileDetails', 'aMobileOrganizer'];
            
            // Smooth scroll for mobile nav
            navLinks.forEach((linkId, index) => {
                $(`#${linkId}`).click(function(e) {
                    e.preventDefault();
                    const targetSection = sections[index];
                    const targetElement = document.getElementById(targetSection);
                    
                    // Remove any existing temp-active class from all buttons
                    navLinks.forEach(id => {
                        $(`#${id}`).removeClass('temp-active');
                    });
                    
                    // Add temporary active state for visual feedback
                    $(this).addClass('temp-active');
                    
                    // Remove active state after brief moment
                    setTimeout(() => {
                        $(this).removeClass('temp-active');
                    }, 200);
                    
                    if (targetElement) {
                        const offsetTop = targetElement.offsetTop - 80; // Offset for top navbar
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }
        
        // Hide/Show mobile bottom button when modals open/close
        const mobileBottomButton = document.querySelector('.mobile-bottom-ticket');
        const modalsToHideButton = ['#getTickets', '#getTicketsMobile', '#checkout', '#checkoutMobile'];
        let openModalsCount = 0;
        
        modalsToHideButton.forEach(modalId => {
            const modal = document.querySelector(modalId);
            if (modal) {
                modal.addEventListener('show.bs.modal', function () {
                    openModalsCount++;
                    if (mobileBottomButton) {
                        mobileBottomButton.style.display = 'none';
                    }
                });
                
                modal.addEventListener('hidden.bs.modal', function () {
                    openModalsCount--;
                    // Only show the button if no other related modals are open
                    if (openModalsCount <= 0 && mobileBottomButton) {
                        mobileBottomButton.style.display = 'block';
                        openModalsCount = 0; // Reset counter to prevent negative values
                    }
                });
            }
        });
    });
    
    // Handle mobile/tablet Get Tickets button
    document.addEventListener('DOMContentLoaded', function() {
        const mobileGetTicketsBtn = document.getElementById('mobileGetTicketsBtn');
        const desktopCheckoutBtn = document.getElementById('desktopCheckoutBtn');
        
        if (mobileGetTicketsBtn) {
            mobileGetTicketsBtn.addEventListener('click', function() {
                // Check if screen width is tablet (768px - 991px) to use desktop modal
                if (window.innerWidth >= 768 && window.innerWidth <= 991) {
                    // Use desktop modal for tablets
                    const desktopModal = new bootstrap.Modal(document.getElementById('getTickets'));
                    desktopModal.show();
                } else {
                    // Use mobile modal for smaller screens
                    const mobileModal = new bootstrap.Modal(document.getElementById('getTicketsMobile'));
                    mobileModal.show();
                }
            });
        }
        
        if (desktopCheckoutBtn) {
            desktopCheckoutBtn.addEventListener('click', function(event) {
                if (validateTicketSelection(event)) {
                    $('#getTickets').modal('hide');
                    $('#checkout').modal('show');
                }
            });
        }
    });
</script>
@endif

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.fever-carousel-slide');
    const dots = document.querySelectorAll('.fever-dot');

    function showSlide(index) {
        if (!slides.length) return;
        if (index >= slides.length) currentSlide = 0;
        else if (index < 0) currentSlide = slides.length - 1;
        else currentSlide = index;

        slides.forEach((s, i) => {
            s.classList.toggle('active', i === currentSlide);
        });
        dots.forEach((d, i) => {
            d.classList.toggle('active', i === currentSlide);
        });
    }

    function nextSlide() { showSlide(currentSlide + 1); }
    function prevSlide() { showSlide(currentSlide - 1); }
    function goToSlide(i) { showSlide(i); }

    if (slides.length > 1) {
        setInterval(nextSlide, 5000);
    }

    // Smooth tab scrolling and active state
    document.querySelectorAll('.fever-tab-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                const offset = 80;
                const bodyRect = document.body.getBoundingClientRect().top;
                const elementRect = targetEl.getBoundingClientRect().top;
                const elementPosition = elementRect - bodyRect;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                document.querySelectorAll('.fever-tab-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
</script>


<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.fever-carousel-slide');
    const dots = document.querySelectorAll('.fever-dot');

    function showSlide(index) {
        if (!slides.length) return;
        if (index >= slides.length) currentSlide = 0;
        else if (index < 0) currentSlide = slides.length - 1;
        else currentSlide = index;

        slides.forEach((s, i) => {
            s.classList.toggle('active', i === currentSlide);
        });
        dots.forEach((d, i) => {
            d.classList.toggle('active', i === currentSlide);
        });
    }

    function nextSlide() { showSlide(currentSlide + 1); }
    function prevSlide() { showSlide(currentSlide - 1); }
    function goToSlide(i) { showSlide(i); }

    if (slides.length > 1) {
        setInterval(nextSlide, 5000);
    }

    // Smooth tab scrolling and active state
    document.querySelectorAll('.fever-tab-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                const offset = 80;
                const bodyRect = document.body.getBoundingClientRect().top;
                const elementRect = targetEl.getBoundingClientRect().top;
                const elementPosition = elementRect - bodyRect;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                document.querySelectorAll('.fever-tab-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
</script>


<script>
    let currentGallerySlide = 0;
    const gSlides = document.querySelectorAll('.fever-gallery-slide');
    const gDots = document.querySelectorAll('.fever-gdot');

    function showGallerySlide(index) {
        if (!gSlides.length) return;
        if (index >= gSlides.length) currentGallerySlide = 0;
        else if (index < 0) currentGallerySlide = gSlides.length - 1;
        else currentGallerySlide = index;

        gSlides.forEach((s, i) => {
            s.classList.toggle('active', i === currentGallerySlide);
        });
        gDots.forEach((d, i) => {
            d.classList.toggle('active', i === currentGallerySlide);
        });
    }

    function nextGallerySlide() { showGallerySlide(currentGallerySlide + 1); }
    function prevGallerySlide() { showGallerySlide(currentGallerySlide - 1); }
    function goToGallerySlide(i) { showGallerySlide(i); }

    // Smooth tab scrolling and active state
    document.querySelectorAll('.fever-tab-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                const offset = 80;
                const bodyRect = document.body.getBoundingClientRect().top;
                const elementRect = targetEl.getBoundingClientRect().top;
                const elementPosition = elementRect - bodyRect;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                document.querySelectorAll('.fever-tab-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
</script>

@endpush
