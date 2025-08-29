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
               height: 140px; 
               box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.16);
            }
            #getTicketsBottom1{
               display: block;
               height: 140px; 
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
            margin-bottom: 20px;
            .image-container {
                position: relative;
                border-radius: 25px;
                overflow: hidden;
            }
            .image-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.1); /* Fondo semitransparente */
            }   
            .card-img-top{
                border-radius: 25px;
                height: 380px;
                width: 100%;
                object-fit: cover;
            }
            .event-title-container {
                position: absolute;
                bottom: 10px;
                left: 10%;
                transform: translateX(-10%);
                display: flex;
                align-items: center;
                z-index: 1;
                width: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                margin: 0px;
            }

            .event-date-circle {
                background-color: #D9BC73;
                border: 1px solid #ffff;
                border-radius: 50%;
                color: white;
                text-align: center;
                width: 80px;
                height: 80px;
                .event-date {
                    margin:0px;
                }
            }

            .event-title {
                color: white;
                font-size: 1rem;
                margin-left: 10px;
                width: 280px;
                font-weight: bold;
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
            .card-img-top{
                height: 300px;
            }
            .event-date-circle {
                width: 60px;
                height: 60px;
                .event-date {
                    font-size: 12px;
                }
           }
           .event-title {
                width: 200px;
            }
        }

        /* Mobile Navigation Bottom Bar */
        @media (max-width: 959px) {
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
                border-top: 2px solid #D9BC73;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
                z-index: 1050;
                padding: 12px 0;
                backdrop-filter: blur(10px);
            }

            .mobile-bottom-nav .container-fluid {
                padding: 0 15px;
            }

            .mobile-bottom-nav .nav-link {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 10px 8px;
                color: #6c757d;
                text-decoration: none;
                border-radius: 12px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                font-size: 0.7rem;
                font-weight: 600;
                background: transparent;
                border: 2px solid transparent;
                min-height: 60px;
                position: relative;
                overflow: hidden;
            }

            .mobile-bottom-nav .nav-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, #D9BC73, #E8D399);
                opacity: 0;
                transition: opacity 0.3s ease;
                border-radius: 10px;
                z-index: -1;
            }

            .mobile-bottom-nav .nav-link:hover::before,
            .mobile-bottom-nav .nav-link.active::before {
                opacity: 1;
            }

            .mobile-bottom-nav .nav-link:hover,
            .mobile-bottom-nav .nav-link.active {
                color: #fff;
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(217, 188, 115, 0.4);
                border-color: #D9BC73;
            }

            .mobile-bottom-nav .nav-link i {
                font-size: 1.3rem;
                margin-bottom: 4px;
                transition: transform 0.3s ease;
                z-index: 1;
                position: relative;
            }

            .mobile-bottom-nav .nav-link:hover i,
            .mobile-bottom-nav .nav-link.active i {
                transform: scale(1.1);
            }

            .mobile-bottom-nav .nav-link span {
                z-index: 1;
                position: relative;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            /* Hide original mobile nav */
            #navPhone {
                display: none !important;
            }

            /* Add bottom margin to prevent content overlap with fixed navbar and tickets button */
            .main-content {
                margin-bottom: 160px !important; /* Space for both navbars */
            }

            /* Adjust the get tickets bottom bar position */
            #getTicketsBottom1 {
                bottom: 80px !important; /* Above the navigation bar */
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
