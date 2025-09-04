<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="76x76" href="">
    <link rel="icon" type="image/png" href="">
    <title>
        @if (Route::is('event.show'))
            @if ($event->meta_title != null)
                {{ $event->meta_title . ' | ' . $event->meta_description }}
            @else    
                {{ $event->title . ' - ' . date('j F, Y (h:s a)', strtotime($event->date_time_start)) . ' | Ticketplatform'}}
            @endif
        @else
            Ticket Platform
        @endif
    </title>
    @if (Route::is('event.show'))    
        @php
            $fecha_inicial = $event->date_time_start ;
            $fecha = DateTime::createFromFormat('Y-m-d H:i', $fecha_inicial);
            $fecha_formateada = $fecha->format('Y-m-d\TH:i');
            $offset = timezone_offset_get($fecha->getTimezone(), $fecha);
            $fecha_final_start = $fecha_formateada . sprintf('%+03d:%02d', $offset / 3600, abs($offset) % 3600 / 60);

            $fecha_inicial = $event->date_time_end ;
            $fecha = DateTime::createFromFormat('Y-m-d H:i', $fecha_inicial);
            $fecha_formateada = $fecha->format('Y-m-d\TH:i');
            $offset = timezone_offset_get($fecha->getTimezone(), $fecha);
            $fecha_final_end = $fecha_formateada . sprintf('%+03d:%02d', $offset / 3600, abs($offset) % 3600 / 60);
        @endphp
        <script type="application/ld+json">
            {
            "@context": "https://schema.org",
            "@type": "Event",
            "name": "{{ $event->title }}",
            "startDate": "{{ $fecha_final_start }}",
            "endDate": "{{ $fecha_final_end }}",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "eventStatus": "https://schema.org/EventScheduled",
            "location": {
                "@type": "Place",
                "name": "{{ $event->ubication }}",
                "address": {
                "@type": "PostalAddress",
                "streetAddress": "{{ $event->street_address }}",
                "addressLocality": "{{ $event->address_locality }}",
                "postalCode": "{{ $event->postal_code }}",
                "addressRegion": "{{ $event->address_region }}",
                "addressCountry": "{{ $event->address_country }}"
                }
            },
            "image": [
                "{{ asset('storage/' .  $event->image) }}"
            ],
            "description": "{{ $event->summary }}",
            "offers": {
                "@type": "Offer",
                "url": "{{ request()->url() }}",
            },
            "performer": {
                "@type": "PerformingGroup",
                "name": "{{ $event->user->username }}"
            },
            "organizer": {
                "@type": "Organization",
                "name": "{{ $event->user->username }}",
                "url": "{{ $event->user->web_url }}"
            }
            }
        </script>
    @endif
    
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}?v={{ env('VERSION_CSS') }}" rel="stylesheet" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.3/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
    
    <style>
        .sold-out-overlay {
            position: absolute;
            top: 50%;
            left: 35%;
            transform: translate(-50%, -50%);
            z-index: 10;
            opacity: 0.8;
            pointer-events: none;
            width: auto;
        }
        .opacity-25 {
            opacity: 0.25;
        }
        .img-fluid-sold-out {
            width: 50%;
            height: 50%;
        }
        .bgcolor-dark {
            background-color: #0E1012 !important;
        }
        .modal-border{
            border: 2px solid #7d7d7d;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: none !important;
            border: none !important;
        }

        .alert-danger {
           color: #fff !important;
        }
        .row {
            --bs-gutter-x: -0.5rem !important; 
        }
        .nav-event {
            box-shadow: none !important;
        }
        .nav-event .col-6 {
            border-bottom: solid 1px rgb(221,221,221);

        }
        .sticky-top {
            padding-top: 15px;
        }
        .about-html {
            padding-top: 20px;
            text-align: justify;
            
        }
        .about-html p {
            font-size: 0.80rem;            
        }
        .tox-tinymce-aux {
            display: none !important;
        }
        #checkout .row {
            --bs-gutter-x: 1.5rem !important; 
        }
        #checkoutMobile .row {
            --bs-gutter-x: 1.5rem !important; 
        }
        #navPhone {
            display: none;
        }
        #successpage .mobile {
            display: none;
        }
        #whenandwhere {
            padding-top: 40px;
        }
        #whPhone{
            display: none;
        }
        #getTicketsBottom {
            display: none; 
        }
        #getTicketsBottom1 {
            display: none; 
        }
        #cardGetTickets {
               display: block; 
        }
        #sucesspage .mobile {
            display: none;
        }
        .card-no-border{
            border-radius: 0px !important;
        }
        @media only screen and (max-width: 959px) {
            .coverimg {
                background-repeat: round !important;
            }
            #cardGetTickets {
               display: none !important; 
            }
            #getTicketsBottom {
               display: block;
               height: 80px; 
               box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.16);
            }
            #getTicketsBottom1{
               display: block;
               height: 80px; 
               position: sticky !important;
               background-color: #ffff;
            }
            #whDesktop {
                display: none;
            }
            #whPhone {
            display: block;
            }
            #navDesktop {
                display: none;
            }
            #navPhone {
                display: block;
            }
            #sucesspage .desktop {
                display: none;
            }
            #sucesspage .mobile {
                display: block;
            }
            
        }
        @media only screen and (max-width: 575px) {
            .locationDiv {
                padding-top: 20px !important;
            }
        }
        .hover-event-list:hover{
            color: currentColor;
        }
        .header {
            
            padding: 20px;
            text-align: center;
            background-color: #0E1012;
            .logo {
                width: 200px;
            }
            
        }
        .subheader {
            color: #0E1012;
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 0.2rem;
            text-align: center;
        }
        .events-main {
            min-height: 100vh;
            /* background-color: #0E1012; */
        }
        .list-event {
            width: 380px;
            position: relative;
            margin-bottom: 30px;
            border-radius: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            .image-container {
                position: relative;
                border-radius: 25px 25px 0 0;
                overflow: hidden;
            }
            .image-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.05); /* Ligero overlay */
                z-index: 1;
            }   
            .card-img-top{
                border-radius: 25px 25px 0 0;
                height: auto;
                width: 100%;
                object-fit: contain;
                max-height: 500px;
                display: block;
            }

            /* New event info container below image */
            .event-info-container {
                display: flex;
                align-items: center;
                padding: 15px 20px;
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                border-radius: 0 0 25px 25px;
                gap: 15px;
                min-height: 80px;
            }

            .event-date-circle {
                background: linear-gradient(135deg, #D9BC73, #E8D399);
                border: 2px solid #fff;
                border-radius: 50%;
                color: white;
                text-align: center;
                width: 70px;
                height: 70px;
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                box-shadow: 0 4px 10px rgba(217, 188, 115, 0.3);
                .event-date {
                    margin: 0;
                    font-size: 0.75rem;
                    font-weight: 700;
                    line-height: 1;
                }
            }

            .event-title {
                color: #2d3748;
                font-size: 1rem;
                font-weight: 600;
                line-height: 1.3;
                flex: 1;
                text-align: left;
                margin: 0;
            }

            /* Hover effects */
            &:hover {
                transform: translateY(-8px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                .event-date-circle {
                    transform: scale(1.05);
                    box-shadow: 0 6px 15px rgba(217, 188, 115, 0.4);
                }
                .event-title {
                    color: #D9BC73;
                }
            }
        }
        .events-container {
            padding: 0px !important;
        }
        .btn-yellow{
            background-color: #D9BC73;
            color: #fff;
        }
        @media only screen and (max-width: 1400px) {
           .list-event {
            width: 300px;
            margin-bottom: 25px;
            .card-img-top{
                height: auto;
                max-height: 400px;
            }
            .event-date-circle {
                width: 60px;
                height: 60px;
                .event-date {
                    font-size: 0.65rem;
                }
           }
           .event-title {
                font-size: 0.9rem;
            }
           .event-info-container {
                padding: 12px 15px;
                gap: 12px;
                min-height: 70px;
            }
        }

        /* Mobile Navigation Top Bar */
        @media (max-width: 959px) {
            .mobile-top-nav {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: #fff;
                border-bottom: 2px solid #D9BC73;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                z-index: 1050;
                padding: 8px 0;
                backdrop-filter: blur(10px);
            }

            .mobile-top-nav .container-fluid {
                padding: 0 15px;
            }

            .mobile-top-nav .nav-link {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 8px 12px;
                color: #6c757d;
                text-decoration: none;
                border-radius: 20px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                font-size: 0.8rem;
                font-weight: 600;
                background: transparent;
                border: 2px solid transparent;
                min-height: 40px;
                position: relative;
                overflow: hidden;
            }

            .mobile-top-nav .nav-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, #D9BC73, #E8D399);
                opacity: 0;
                transition: opacity 0.3s ease;
                border-radius: 18px;
                z-index: -1;
            }

            .mobile-top-nav .nav-link:hover::before,
            .mobile-top-nav .nav-link.temp-active::before {
                opacity: 1;
            }

            .mobile-top-nav .nav-link:hover,
            .mobile-top-nav .nav-link.temp-active {
                color: #fff;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(217, 188, 115, 0.4);
            }

            .mobile-top-nav .nav-link span {
                z-index: 1;
                position: relative;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            /* Mobile Bottom Get Tickets Button */
            .mobile-bottom-ticket {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
                border-top: 2px solid #D9BC73;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
                z-index: 1050;
                padding: 8px 15px;
                backdrop-filter: blur(10px);
            }

            .mobile-bottom-ticket .btn-yellow {
                background: linear-gradient(135deg, #D9BC73, #E8D399);
                border: none;
                border-radius: 20px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(217, 188, 115, 0.3);
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .mobile-bottom-ticket .btn-yellow:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(217, 188, 115, 0.5);
            }

            /* Adjust main content padding for fixed navbars */
            .main-content {
                padding-top: 60px !important;
                padding-bottom: 0 !important; /* Use margin-bottom on last element instead */
            }

            /* Hide original mobile nav */
            #navPhone {
                display: none !important;
            }

            /* Control exact scroll limit - no footer, just button container at bottom */
            .main-content .row:last-of-type {
                margin-bottom: 80px !important; /* Exactly navbar height - no extra space */
            }

            /* Remove conflicting bottom margin */
            .main-content {
                margin-bottom: 0 !important; /* Remove extra margin, use precise control above */
            }

            /* Adjust the get tickets bottom bar position */
            #getTicketsBottom1 {
                bottom: 0px !important; /* Directly at bottom now */
            }
        }

        /* Event Summary Styling */
        .event-summary-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-left: 4px solid #D9BC73;
            border-radius: 8px;
            padding: 20px 25px;
            margin: 10px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .event-summary-content {
            font-size: 1rem;
            line-height: 1.6;
            color: #495057;
            font-weight: 400;
            text-align: left;
            font-family: 'Open Sans', sans-serif;
            letter-spacing: 0.3px;
        }

        @media (max-width: 768px) {
            .event-summary-container {
                padding: 15px 20px;
                margin: 8px 0;
            }
            
            .event-summary-content {
                font-size: 0.9rem;
                line-height: 1.5;
            }
        }

        /* Section Divider Styling */
        .section-divider {
            width: 90%;
            height: 1px;
            background: rgba(0, 0, 0, 0.15);
            margin: 30px auto 25px auto;
            border-radius: 1px;
        }

        /* Mobile Footer Styling - DISABLED */
        .mobile-footer {
            display: none !important; /* Hidden on all devices now */
        }

        /* Mobile Ticket Selection Styling */
        @media (max-width: 768px) {
            .section-divider {
                width: 90%;
                margin: 20px auto 20px auto;
            }

            /* Improve mobile ticket selector visibility */
            .ticket-select-mobile {
                font-size: 1.1rem !important;
                font-weight: 600 !important;
                text-align: center !important;
                min-width: 50px !important;
                height: 40px !important;
                border: none !important;
                border-radius: 8px !important;
                background-color: #fff !important;
                color: #333 !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            }

            /* Modern ticket card design similar to reference image */
            .ticket-card-mobile {
                background-color: #fff !important;
                border-radius: 12px !important;
                padding: 0 !important;
                margin-bottom: 16px !important;
                border: 1px solid #e9ecef !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
                overflow: hidden !important;
                transition: box-shadow 0.3s ease !important;
            }

            .ticket-card-mobile:hover {
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.16) !important;
            }

            /* Top section - ticket type */
            .ticket-type-section {
                background-color: #f8f9fa !important;
                padding: 12px 16px !important;
                border-bottom: 1px solid #e9ecef !important;
            }

            /* Bottom section - event title and controls */
            .ticket-controls-section {
                background-color: #fff !important;
                padding: 16px !important;
            }

            /* Ticket type styling */
            .ticket-type-mobile {
                font-size: 1rem !important;
                font-weight: 600 !important;
                color: #495057 !important;
                margin: 0 !important;
            }

            /* Event title styling */
            .event-title-mobile {
                font-size: 0.9rem !important;
                font-weight: 500 !important;
                color: #6c757d !important;
                margin-bottom: 12px !important;
                line-height: 1.3 !important;
                word-wrap: break-word !important;
            }

            /* Ticket price styling */
            .ticket-price-mobile {
                font-size: 1.2rem !important;
                font-weight: 700 !important;
                color: #343a40 !important;
                margin-bottom: 2px !important;
            }

            .ticket-fee-mobile {
                font-size: 0.8rem !important;
                color: #6c757d !important;
                margin-left: 4px !important;
            }

            /* Sales end date styling - moved down */
            .ticket-sales-end {
                font-size: 0.75rem !important;
                color: #6c757d !important;
                margin-top: 6px !important;
                line-height: 1.2 !important;
            }

            /* Square buttons with proper colors */
            .btn-ticket-minus {
                width: 36px !important;
                height: 36px !important;
                border-radius: 6px !important;
                border: none !important;
                background-color: #dee2e6 !important;
                color: #6c757d !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 0.9rem !important;
                font-weight: 600 !important;
                transition: all 0.2s ease !important;
                flex-shrink: 0 !important;
            }

            .btn-ticket-plus {
                width: 36px !important;
                height: 36px !important;
                border-radius: 6px !important;
                border: none !important;
                background-color: #343a40 !important;
                color: #fff !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 0.9rem !important;
                font-weight: 600 !important;
                transition: all 0.2s ease !important;
                flex-shrink: 0 !important;
            }

            .btn-ticket-minus:hover {
                background-color: #ced4da !important;
                color: #495057 !important;
            }

            .btn-ticket-plus:hover {
                background-color: #495057 !important;
                color: #fff !important;
            }

            .btn-ticket-minus:disabled,
            .btn-ticket-plus:disabled {
                background-color: #f8f9fa !important;
                color: #adb5bd !important;
                cursor: not-allowed !important;
            }

            /* Ticket controls container - better centered */
            .ticket-controls-mobile {
                display: flex !important;
                align-items: flex-start !important;
                justify-content: space-between !important;
                gap: 8px !important;
            }

            .ticket-info-section {
                flex: 1 !important;
                min-width: 0 !important;
                padding-right: 8px !important;
            }

            .ticket-quantity-controls {
                display: flex !important;
                align-items: center !important;
                gap: 10px !important;
                flex-shrink: 0 !important;
                margin-left: auto !important;
            }

            /* Responsive adjustments for small phones */
            @media (max-width: 360px) {
                .ticket-controls-section {
                    padding: 12px !important;
                }
                
                .ticket-info-section {
                    padding-right: 6px !important;
                }
                
                .ticket-quantity-controls {
                    gap: 8px !important;
                }
                
                .btn-ticket-minus,
                .btn-ticket-plus {
                    width: 32px !important;
                    height: 32px !important;
                    font-size: 0.8rem !important;
                }
                
                .ticket-select-mobile {
                    min-width: 45px !important;
                    height: 32px !important;
                    font-size: 1rem !important;
                }
            }

            /* Override old button styles for mobile tickets */
            .btn.px-3 {
                padding: 8px 12px !important;
                font-size: 0.9rem !important;
                min-width: 40px !important;
                height: 40px !important;
            }

            /* Ensure proper spacing in ticket selection container */
            .d-flex.align-items-center.justify-content-center {
                gap: 8px !important;
            }

            /* Fix mobile modal bottom navigation positioning */
            #getTicketsBottom {
                bottom: 15px !important; /* Move up from bottom edge */
                padding: 15px 0 !important; /* More internal padding */
                height: auto !important; /* Auto height instead of fixed 80px */
                min-height: 100px !important; /* Minimum height for content */
                background-color: #fff !important;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15) !important;
            }

            /* Ensure buttons have proper spacing and visibility */
            #getTicketsBottom .btn-lg {
                padding: 12px 20px !important;
                font-size: 1rem !important;
                margin-bottom: 10px !important; /* Space from bottom */
            }

            /* Add padding to modal body to prevent content overlap with fixed bottom */
            .modal-fullscreen .modal-body {
                padding-bottom: 140px !important; /* Ensure content doesn't hide behind bottom nav */
            }

            /* Adjust total display spacing */
            #getTicketsBottom .row {
                margin-bottom: 10px !important;
                padding: 0 5px !important;
            }
        }
    </style>
    
</head>

<body class="{{ $class ?? '' }}">

    @guest
        @yield('content')
    @endguest

    @auth
        @if (in_array(request()->route()->getName(), ['sign-in-static', 'sign-up-static', 'login', 'register', 'recover-password', 'rtl', 'virtual-reality', 'event.show', 'event.list-events', 'successpage', 'privacy-policy']))
            @yield('content')
        @else
            @if (!in_array(request()->route()->getName(), ['profile', 'profile-static']))
                <div class="min-height-300 bg-primary position-absolute w-100"></div>
            @elseif (in_array(request()->route()->getName(), ['profile-static', 'profile']))
                <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
                    <span class="mask bg-primary opacity-6"></span>
                </div>
            @endif
            @include('layouts.navbars.auth.sidenav')
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @include('components.fixed-plugin')
        @endif
    @endauth

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/argon-dashboard.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.3/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/vfs_fonts.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    @stack('js')
</body>

</html>
