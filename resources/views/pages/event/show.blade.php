@extends('layouts.app', ['class' => 'fever-event-body'])

@section('content')
<style>
    /* Estilos Fever-like en tema claro y elegante - Ultra responsivo (320px+) */
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

    /* Reset preventivo contra desbordamiento */
    *, *::before, *::after {
        box-sizing: border-box;
    }

    html, body {
        max-width: 100vw;
        overflow-x: hidden !important;
    }

    body {
        background-color: #ffffff !important;
        color: #1f2937 !important;
        font-family: 'Open Sans', sans-serif;
        margin: 0;
        padding: 0;
    }

    .fever-event-page {
        background-color: #ffffff;
        padding-bottom: 90px;
        width: 100%;
        max-width: 100vw;
        overflow-x: hidden;
    }

    /* Top navigation header */
    .fever-nav-header {
        background-color: #0E1012;
        padding: 12px 16px;
        width: 100%;
    }

    @media (min-width: 992px) {
        .fever-nav-header {
            padding: 12px 20px;
        }
    }

    .fever-nav-header-inner {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .fever-nav-header .logo {
        max-height: 36px;
        width: auto;
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
        min-height: 220px;
        max-height: 520px;
    }

    @media (max-width: 576px) {
        .fever-hero-media-wrapper {
            min-height: 180px;
            max-height: 300px;
        }
    }

    .fever-hero-media-content {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fever-hero-video {
        width: 100%;
        max-height: 500px;
        object-fit: contain;
        background-color: #0E1012;
        display: block;
        pointer-events: none; /* Disables right click context / pause on click */
    }

    /* Fever floating action buttons on video */
    .fever-hero-video-controls {
        position: absolute;
        bottom: 20px;
        right: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        z-index: 999;
        pointer-events: auto;
    }

    .fever-hero-control-btn {
        background: rgba(0, 0, 0, 0.6);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s ease;
        backdrop-filter: blur(6px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        padding: 0;
    }

    .fever-hero-control-btn:hover {
        background: var(--fever-gold);
        color: #000000;
        transform: scale(1.08);
    }

    .fever-hero-control-btn i {
        font-size: 0.95rem;
    }

    @media (max-width: 576px) {
        .fever-hero-video-controls {
            bottom: 12px;
            right: 12px;
            gap: 8px;
        }
        .fever-hero-control-btn {
            width: 34px;
            height: 34px;
        }
        .fever-hero-control-btn i {
            font-size: 0.82rem;
        }
    }

    .fever-hero-image {
        width: 100%;
        max-height: 500px;
        object-fit: contain;
        background-color: #0E1012;
    }

    /* Hero Text Overlay (Bottom-Left inside video/image, text in white) */
    .fever-hero-caption-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 60px 70px 20px 16px;
        background: linear-gradient(to top, rgba(14, 16, 18, 0.92) 0%, rgba(14, 16, 18, 0.6) 55%, transparent 100%);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        pointer-events: none;
        z-index: 20;
    }

    @media (min-width: 992px) {
        .fever-hero-caption-overlay {
            padding: 70px 80px 24px 20px;
        }
    }

    .fever-hero-caption-overlay > * {
        pointer-events: auto;
    }

    .fever-hero-caption-overlay .fever-badge-category {
        background-color: rgba(217, 188, 115, 0.2);
        color: #f7e7be;
        border: 1px solid var(--fever-gold);
        margin-bottom: 8px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        flex-shrink: 0;
    }

    .fever-hero-caption-overlay .fever-title {
        color: #ffffff;
        margin-bottom: 8px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
    }

    .fever-hero-caption-overlay .fever-key-points {
        color: rgba(255, 255, 255, 0.9);
        gap: 12px 20px;
        font-size: 0.9rem;
    }

    .fever-hero-caption-overlay .fever-key-points i {
        color: var(--fever-gold);
    }

    .fever-rating-container {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 2px;
        margin-bottom: 6px;
    }

    .fever-rating-stars {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        color: var(--fever-gold);
        font-size: 0.95rem;
    }

    .fever-rating-number {
        font-weight: 700;
        color: #ffffff;
        font-size: 0.95rem;
        margin-left: 4px;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
    }

    .fever-rating-scale {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.82rem;
        font-weight: 500;
    }

    @media (max-width: 576px) {
        .fever-hero-caption-overlay {
            padding: 30px 55px 12px 14px;
        }
        .fever-hero-caption-overlay .fever-title {
            font-size: 1.25rem;
            margin-bottom: 6px;
        }
        .fever-hero-caption-overlay .fever-key-points {
            font-size: 0.78rem;
            gap: 5px 10px;
        }
        .fever-hero-caption-overlay .fever-badge-category {
            font-size: 0.65rem;
            padding: 3px 8px;
            margin-bottom: 4px;
            white-space: nowrap;
        }
        .fever-rating-container {
            margin-top: 1px;
            margin-bottom: 4px;
            gap: 4px;
        }
        .fever-rating-stars {
            font-size: 0.8rem;
            gap: 1.5px;
        }
        .fever-rating-number {
            font-size: 0.8rem;
        }
        .fever-rating-scale {
            font-size: 0.72rem;
        }
    }

    /* Event Title Header & Key Metadata */
    .fever-event-header-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 16px 16px 16px;
        background-color: #ffffff;
    }

    @media (min-width: 768px) {
        .fever-event-header-container {
            padding: 32px 20px 20px 20px;
        }
    }

    .fever-badge-category {
        display: inline-block;
        background-color: #fef9ed;
        color: var(--fever-gold-dark);
        border: 1px solid var(--fever-gold);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .fever-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--fever-dark);
        margin-bottom: 12px;
        line-height: 1.25;
        word-break: break-word;
    }

    @media (min-width: 768px) {
        .fever-title {
            font-size: 2.2rem;
            margin-bottom: 16px;
        }
    }

    .fever-key-points {
        display: flex;
        flex-wrap: wrap;
        gap: 12px 18px;
        color: var(--fever-gray-600);
        font-size: 0.88rem;
        padding-bottom: 6px;
    }

    @media (min-width: 768px) {
        .fever-key-points {
            gap: 20px 24px;
            font-size: 0.95rem;
        }
    }

    .fever-key-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }

    .fever-key-item i {
        color: var(--fever-gold-dark);
        font-size: 0.95rem;
    }

    /* Sticky Navigation Tabs (Responsive & Touch-friendly) */
    .fever-sticky-tabs {
        position: sticky;
        top: 0;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(8px);
        border-top: 1px solid var(--fever-gray-200);
        border-bottom: 1px solid var(--fever-gray-200);
        z-index: 100;
        padding: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        width: 100%;
    }

    .fever-tabs-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
        display: flex;
        gap: 20px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    @media (min-width: 992px) {
        .fever-tabs-wrapper {
            padding: 0 20px;
        }
    }

    @media (min-width: 768px) {
        .fever-tabs-wrapper {
            gap: 32px;
        }
    }

    .fever-tabs-wrapper::-webkit-scrollbar {
        display: none;
    }

    .fever-tab-link {
        color: var(--fever-gray-500);
        text-decoration: none;
        padding: 12px 0;
        font-weight: 600;
        font-size: 0.88rem;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        white-space: nowrap;
        flex-shrink: 0;
    }

    @media (min-width: 768px) {
        .fever-tab-link {
            padding: 16px 0;
            font-size: 0.95rem;
        }
    }

    .fever-tab-link:hover, .fever-tab-link.active {
        color: #000000;
        border-bottom-color: var(--fever-gold-dark);
    }

    /* Content Grid Layout */
    .fever-content-grid {
        width: 100%;
        max-width: 1200px;
        margin: 20px auto 0 auto;
        padding: 0 16px;
        display: grid;
        grid-template-columns: 100%;
        gap: 24px;
    }

    @media (min-width: 992px) {
        .fever-content-grid {
            margin-top: 32px;
            padding: 0 20px;
            grid-template-columns: 1fr 380px;
            gap: 40px;
        }
    }

    .fever-main-column {
        min-width: 0; /* Prevents flex/grid blowouts */
        width: 100%;
    }

    /* Section Cards */
    .fever-section {
        background: #ffffff;
        border: 1px solid var(--fever-gray-200);
        border-radius: 14px;
        padding: 18px 16px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        width: 100%;
        min-width: 0;
        overflow: hidden;
    }

    @media (min-width: 768px) {
        .fever-section {
            padding: 28px;
            margin-bottom: 28px;
        }
    }

    .fever-section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--fever-dark);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    @media (min-width: 768px) {
        .fever-section-title {
            font-size: 1.35rem;
            margin-bottom: 20px;
            gap: 10px;
        }
    }

    .fever-section-title i {
        color: var(--fever-gold-dark);
    }

    /* Compact Fever-style Multi-Item Gallery Carousel */
    .fever-gallery-box {
        background-color: #f3f4f6;
        border-radius: 12px;
        padding: 16px 12px;
        position: relative;
        width: 100%;
    }

    @media (min-width: 768px) {
        .fever-gallery-box {
            border-radius: 16px;
            padding: 24px;
        }
    }

    .fever-gallery-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #4b5563;
        margin-bottom: 12px;
    }

    .fever-gallery-slider-wrap {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .fever-gallery-track {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
        scrollbar-width: none;
        padding: 4px 2px;
        width: 100%;
    }

    .fever-gallery-track::-webkit-scrollbar {
        display: none;
    }

    .fever-gallery-card {
        flex: 0 0 calc(50% - 6px);
        min-width: 100px;
        aspect-ratio: 1 / 1;
        border-radius: 10px;
        overflow: hidden;
        background-color: #000;
        cursor: pointer;
        position: relative;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    @media (min-width: 576px) {
        .fever-gallery-card {
            flex: 0 0 calc(33.333% - 8px);
            min-width: 115px;
        }
    }

    @media (min-width: 768px) {
        .fever-gallery-card {
            flex: 0 0 calc(25% - 11px);
            min-width: 130px;
            border-radius: 12px;
        }
    }

    .fever-gallery-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
    }

    .fever-gallery-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .fever-gallery-video-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(0, 0, 0, 0.65);
        color: #fff;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
    }

    .fever-slider-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.95);
        color: #111827;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        transition: all 0.2s;
    }

    @media (min-width: 768px) {
        .fever-slider-nav-btn {
            width: 38px;
            height: 38px;
        }
    }

    .fever-slider-nav-btn:hover {
        background: var(--fever-gold);
        color: #000;
    }

    .fever-slider-prev {
        left: -8px;
    }

    .fever-slider-next {
        right: -8px;
    }

    @media (min-width: 768px) {
        .fever-slider-prev { left: -16px; }
        .fever-slider-next { right: -16px; }
    }

    /* Lightbox Modal */
    .fever-lightbox-modal .modal-content {
        background-color: transparent;
        border: none;
    }
    .fever-lightbox-modal .modal-body {
        padding: 0;
        text-align: center;
    }
    .fever-lightbox-media {
        max-width: 100%;
        max-height: 85vh;
        border-radius: 12px;
        object-fit: contain;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
    }

    /* Sticky Sidebar Purchase Box (Desktop) */
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
        padding: 10px 0;
        border-bottom: 1px solid var(--fever-gray-200);
        color: var(--fever-dark);
    }
    .fever-info-pill:last-child {
        border-bottom: none;
    }
    .fever-info-pill i {
        font-size: 1.1rem;
        color: var(--fever-gold-dark);
        width: 22px;
        text-align: center;
        flex-shrink: 0;
    }

    /* Embedded Map */
    .fever-map-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--fever-gray-200);
        height: 240px;
        margin-top: 15px;
        width: 100%;
    }

    @media (min-width: 768px) {
        .fever-map-container {
            height: 280px;
        }
    }

    /* Mobile Sticky Action Bar (Clean, integrated 1-layer) */
    .fever-mobile-sticky-action {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        border-top: 2px solid var(--fever-gold);
        padding: 12px 16px;
        z-index: 1050;
        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    @media (max-width: 991.98px) {
        .fever-mobile-sticky-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .fever-mobile-sticky-action .btn-yellow {
            background: linear-gradient(135deg, #D9BC73, #E8D399);
            color: #0E1012;
            font-weight: 700;
            border: none;
            border-radius: 20px;
            padding: 10px 22px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(217, 188, 115, 0.35);
        }
    }
</style>

<div class="fever-event-page">
    {{-- Header Logo / Navigation --}}
    <div class="fever-nav-header">
        <div class="fever-nav-header-inner">
            <a href="/list-events">
                <img src="{{ asset('img/logos/logo.png') }}" alt="Logo" class="logo">
            </a>
            <a href="/list-events" class="btn btn-sm btn-outline-light mb-0">
                <i class="fas fa-arrow-left me-1"></i> All Events
            </a>
        </div>
    </div>

    @if(session()->has('succes') || session()->has('error') || (isset($errors) && $errors->any()))
        <div id="alert">
            @include('components.alert')
        </div>
    @endif

    {{-- Top of the Event: Single Video or Single Image (No carousel) with bottom-left text overlay --}}
    <div class="fever-hero-media-wrapper">
        <div class="fever-hero-media-content">
            @if($event->cover_video)
                <video class="fever-hero-video" id="feverHeroVideo" autoplay muted loop playsinline preload="auto">
                    <source src="{{ asset('storage/' . $event->cover_video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>

                {{-- Fever floating controls: Mute/Unmute audio toggle --}}
                <div class="fever-hero-video-controls">
                    <button type="button" class="fever-hero-control-btn" id="feverAudioToggleBtn" onclick="window.toggleHeroVideoAudio(event)" title="Activar/Silenciar sonido">
                        <i class="fas fa-volume-mute" id="feverAudioIcon" style="pointer-events: none;"></i>
                    </button>
                </div>
            @elseif($event->coverimage)
                <img src="{{ asset('storage/' . $event->coverimage) }}" class="fever-hero-image" alt="{{ $event->title }}">
            @elseif($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" class="fever-hero-image" alt="{{ $event->title }}">
            @endif

            {{-- Text overlay at bottom-left of video/hero --}}
            <div class="fever-hero-caption-overlay">
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

                @if($event->rating)
                    @php
                        $ratingVal = (float)$event->rating;
                        $fullStars = floor($ratingVal);
                        $hasHalf = ($ratingVal - $fullStars) >= 0.3 && ($ratingVal - $fullStars) <= 0.7;
                        if (($ratingVal - $fullStars) > 0.7) {
                            $fullStars++;
                            $hasHalf = false;
                        }
                        $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);
                    @endphp
                    <div class="fever-rating-container">
                        <div class="fever-rating-stars">
                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            @if($hasHalf)
                                <i class="fas fa-star-half-alt"></i>
                            @endif
                            @for($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>
                        <span class="fever-rating-number">{{ number_format($ratingVal, 1) }}</span>
                        <span class="fever-rating-scale">/ 5.0</span>
                    </div>
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

            {{-- Gallery Section (Compact Fever Multi-Item Carousel) --}}
            @php
                $galleryItems = [];
                if (!empty($event->gallery) && is_array($event->gallery)) {
                    foreach ($event->gallery as $gItem) {
                        if (is_string($gItem) && !empty($gItem)) {
                            $galleryItems[] = $gItem;
                        }
                    }
                }
                if (empty($galleryItems)) {
                    if ($event->image) $galleryItems[] = $event->image;
                    if ($event->coverimage) $galleryItems[] = $event->coverimage;
                }
            @endphp
            <section class="fever-section" id="gallery-section">
                <div class="fever-gallery-box">
                    <div class="fever-gallery-label">Gallery</div>
                    
                    @if(!empty($galleryItems))
                        <div class="fever-gallery-slider-wrap">
                            <button class="fever-slider-nav-btn fever-slider-prev" onclick="scrollGallery(-1)">
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            <div class="fever-gallery-track" id="galleryTrack">
                                @foreach($galleryItems as $index => $item)
                                    @php
                                        $isVideo = preg_match('/\.(mp4|webm|ogg|mov)$/i', $item);
                                    @endphp
                                    <div class="fever-gallery-card" onclick="openLightbox('{{ asset('storage/' . $item) }}', {{ $isVideo ? 'true' : 'false' }})">
                                        @if($isVideo)
                                            <video class="fever-gallery-thumb" muted playsinline preload="metadata">
                                                <source src="{{ asset('storage/' . $item) }}#t=0.5">
                                            </video>
                                            <div class="fever-gallery-video-badge">
                                                <i class="fas fa-play"></i>
                                            </div>
                                        @else
                                            <img src="{{ asset('storage/' . $item) }}" class="fever-gallery-thumb" alt="Gallery photo {{ $index + 1 }}" loading="lazy">
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <button class="fever-slider-nav-btn fever-slider-next" onclick="scrollGallery(1)">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    @else
                        <p class="text-muted mb-0">No gallery items available yet.</p>
                    @endif
                </div>
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

<!-- Modal Lightbox Gallery -->
<div class="modal fade fever-lightbox-modal" id="galleryLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="d-flex justify-content-end p-2">
                <button type="button" class="btn btn-sm btn-dark" data-bs-dismiss="modal" style="border-radius: 50%; width: 36px; height: 36px; padding: 0;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="lightboxMediaContainer">
                {{-- Dynamic content --}}
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
    // Fever Hero Video Mute/Unmute toggle (Exposed globally)
    window.toggleHeroVideoAudio = function(e) {
        if (e) {
            if (typeof e.preventDefault === 'function') e.preventDefault();
            if (typeof e.stopPropagation === 'function') e.stopPropagation();
        }
        var video = document.getElementById('feverHeroVideo');
        var icon = document.getElementById('feverAudioIcon');
        if (!video) return;

        if (video.muted) {
            video.muted = false;
            video.defaultMuted = false;
            video.volume = 1.0;
            var p = video.play();
            if (p !== undefined) {
                p.catch(function(err) {
                    console.log("Audio play error:", err);
                });
            }
            if (icon) {
                icon.className = "fas fa-volume-up";
            }
            console.log("[Audio] UNMUTED - sound is ON");
        } else {
            video.muted = true;
            if (icon) {
                icon.className = "fas fa-volume-mute";
            }
            console.log("[Audio] MUTED - sound is OFF");
        }
    };

    // Compact Fever-style Gallery Scroll & Lightbox
    window.scrollGallery = function(direction) {
        const track = document.getElementById('galleryTrack');
        if (!track) return;
        const scrollAmount = track.clientWidth * 0.75;
        track.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    };

    window.openLightbox = function(mediaUrl, isVideo) {
        const container = document.getElementById('lightboxMediaContainer');
        if (!container) return;

        if (isVideo) {
            container.innerHTML = '<video class="fever-lightbox-media" controls autoplay playsinline style="width: 100%; max-height: 80vh;"><source src="' + mediaUrl + '"></video>';
        } else {
            container.innerHTML = '<img src="' + mediaUrl + '" class="fever-lightbox-media" alt="Enlarged view">';
        }

        const modal = new bootstrap.Modal(document.getElementById('galleryLightboxModal'));
        modal.show();
    };

    // Stop video playback when lightbox closes & attach click handlers
    document.addEventListener('DOMContentLoaded', function() {
        const lightboxEl = document.getElementById('galleryLightboxModal');
        if (lightboxEl) {
            lightboxEl.addEventListener('hidden.bs.modal', function () {
                const container = document.getElementById('lightboxMediaContainer');
                if (container) container.innerHTML = '';
            });
        }


    });
</script>

@endpush
