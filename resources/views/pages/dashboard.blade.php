@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="container-fluid py-4" id="weekly-sales">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-wrap align-items-start gap-2">
                            <div class="me-auto">
                                <h5 class="mb-0">Ventas por evento de la semana</h5>
                                <p class="text-sm text-dark mb-0" id="weeklySalesRange">
                                    Semana del {{ $sales['range_label'] }} (lunes a domingo)
                                    @if ($sales['is_current'])
                                        <span class="badge bg-gradient-primary ms-1">Semana actual</span>
                                    @endif
                                </p>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <a href="{{ route('home', ['week' => $sales['prev_week']]) }}" class="btn btn-outline-dark btn-sm mb-0" id="btnPrevWeek" aria-label="Semana anterior">
                                    <i class="fas fa-chevron-left me-1"></i> Anterior
                                </a>
                                @unless ($sales['is_current'])
                                    <a href="{{ route('home') }}" class="btn btn-outline-dark btn-sm mb-0">Semana actual</a>
                                @endunless
                                <a href="{{ route('home', ['week' => $sales['next_week']]) }}" class="btn btn-outline-dark btn-sm mb-0" id="btnNextWeek" aria-label="Semana siguiente">
                                    Siguiente <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                                <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center gap-2 mb-0">
                                    <label for="weekDate" class="text-sm text-dark mb-0 text-nowrap">Ir a la semana del</label>
                                    <input type="date" id="weekDate" name="date" class="form-control form-control-sm" style="width: 150px;"
                                        value="{{ $sales['start']->format('Y-m-d') }}" required>
                                    <button type="submit" class="btn btn-dark btn-sm mb-0">Ver</button>
                                </form>
                                <button type="button" class="btn btn-primary btn-sm mb-0" id="btnDownloadChart" @disabled(empty($sales['rows']))>
                                    <i class="fas fa-download me-1"></i> Descargar PNG
                                </button>
                            </div>
                        </div>
                        <div class="row mt-3 text-center">
                            <div class="col-6 col-md-3 mb-2">
                                <p class="text-xs text-uppercase text-secondary font-weight-bold mb-0">Ingresos</p>
                                <h6 class="mb-0" id="weeklySalesRevenue">${{ number_format($sales['totals']['revenue'], 2) }} USD</h6>
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <p class="text-xs text-uppercase text-secondary font-weight-bold mb-0">Boletos pagados</p>
                                <h6 class="mb-0">{{ number_format($sales['totals']['paid_tickets']) }}</h6>
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <p class="text-xs text-uppercase text-secondary font-weight-bold mb-0">Boletos gratis</p>
                                <h6 class="mb-0">{{ number_format($sales['totals']['free_tickets']) }}</h6>
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <p class="text-xs text-uppercase text-secondary font-weight-bold mb-0">Eventos</p>
                                <h6 class="mb-0">{{ $sales['totals']['events'] }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @if (empty($sales['rows']))
                            <div class="text-center py-5" id="weeklySalesEmpty">
                                <i class="fas fa-chart-bar fa-2x text-secondary mb-2"></i>
                                <p class="text-sm text-dark mb-0">No hay eventos con fecha en esta semana.</p>
                                <p class="text-xs text-secondary mb-0">Usa los botones Anterior / Siguiente o elige otra fecha.</p>
                            </div>
                        @else
                            <div class="chart" style="position: relative; height: 380px;">
                                <canvas id="weeklySalesChart" role="img" aria-label="Gráfica de ingresos y boletos por evento de la semana del {{ $sales['range_label'] }}"></canvas>
                            </div>
                            <p class="text-xs text-secondary mt-2 mb-0">
                                Eventos cuya fecha de inicio cae en la semana. Ingresos = precio del boleto de las órdenes pagadas con Stripe (sin cargos/fees). Boletos gratis = órdenes sin pago.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Today's Money</p>
                                    <h5 class="font-weight-bolder">
                                        $53,000
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+55%</span>
                                        since yesterday
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Today's Users</p>
                                    <h5 class="font-weight-bolder">
                                        2,300
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+3%</span>
                                        since last week
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">New Clients</p>
                                    <h5 class="font-weight-bolder">
                                        +3,462
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-danger text-sm font-weight-bolder">-2%</span>
                                        since last quarter
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Sales</p>
                                    <h5 class="font-weight-bolder">
                                        $103,430
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+5%</span> than last month
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Sales overview</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-arrow-up text-success"></i>
                            <span class="font-weight-bold">4% more</span> in 2021
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card card-carousel overflow-hidden h-100 p-0">
                    <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                        <div class="carousel-inner border-radius-lg h-100">
                            <div class="carousel-item h-100 active" style="background-image: url('./img/carousel-1.jpg');
            background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-camera-compact text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">Get started with Argon</h5>
                                    <p>There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>
                                </div>
                            </div>
                            <div class="carousel-item h-100" style="background-image: url('./img/carousel-2.jpg');
            background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">Faster way to create web pages</h5>
                                    <p>That’s my skill. I’m not really specifically talented at anything except for the
                                        ability to learn.</p>
                                </div>
                            </div>
                            <div class="carousel-item h-100" style="background-image: url('./img/carousel-3.jpg');
            background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-trophy text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">Share with us your design tips!</h5>
                                    <p>Don’t be afraid to be wrong because you can’t learn anything from a compliment.</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev w-5 me-3" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next w-5 me-3" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card ">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">Sales by Country</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center ">
                            <tbody>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="./img/icons/flags/US.png" alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                <h6 class="text-sm mb-0">United States</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                            <h6 class="text-sm mb-0">2500</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Value:</p>
                                            <h6 class="text-sm mb-0">$230,900</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                            <h6 class="text-sm mb-0">29.9%</h6>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="./img/icons/flags/DE.png" alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                <h6 class="text-sm mb-0">Germany</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                            <h6 class="text-sm mb-0">3.900</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Value:</p>
                                            <h6 class="text-sm mb-0">$440,000</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                            <h6 class="text-sm mb-0">40.22%</h6>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="./img/icons/flags/GB.png" alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                <h6 class="text-sm mb-0">Great Britain</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                            <h6 class="text-sm mb-0">1.400</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Value:</p>
                                            <h6 class="text-sm mb-0">$190,700</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                            <h6 class="text-sm mb-0">23.44%</h6>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="./img/icons/flags/BR.png" alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                <h6 class="text-sm mb-0">Brasil</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                            <h6 class="text-sm mb-0">562</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Value:</p>
                                            <h6 class="text-sm mb-0">$143,960</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                            <h6 class="text-sm mb-0">32.14%</h6>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Categories</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                        <i class="ni ni-mobile-button text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">Devices</h6>
                                        <span class="text-xs">250 in stock, <span class="font-weight-bold">346+
                                                sold</span></span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button
                                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                            class="ni ni-bold-right" aria-hidden="true"></i></button>
                                </div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                        <i class="ni ni-tag text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">Tickets</h6>
                                        <span class="text-xs">123 closed, <span class="font-weight-bold">15
                                                open</span></span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button
                                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                            class="ni ni-bold-right" aria-hidden="true"></i></button>
                                </div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                        <i class="ni ni-box-2 text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">Error logs</h6>
                                        <span class="text-xs">1 is active, <span class="font-weight-bold">40
                                                closed</span></span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button
                                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                            class="ni ni-bold-right" aria-hidden="true"></i></button>
                                </div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                        <i class="ni ni-satisfied text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">Happy users</h6>
                                        <span class="text-xs font-weight-bold">+ 430</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button
                                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                            class="ni ni-bold-right" aria-hidden="true"></i></button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div> --}}
@endsection

@push('js')
    @if (!empty($sales['rows']))
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('weeklySalesChart');
            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            const rows = @json($sales['rows']);
            const rangeLabel = @json('Ventas por evento · ' . $sales['range_label']);
            const fileName = @json('ventas-semana-' . $sales['week'] . '.png');
            const money = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 2 });
            const integer = new Intl.NumberFormat('en-US');

            const splitTitle = function (text, max) {
                const words = String(text).split(/\s+/);
                const lines = [];
                let line = '';
                words.forEach(function (word) {
                    if ((line + ' ' + word).trim().length > max && line !== '') {
                        lines.push(line);
                        line = word;
                    } else {
                        line = (line + ' ' + word).trim();
                    }
                });
                if (line !== '') {
                    lines.push(line);
                }
                return lines.slice(0, 2).map(function (l, i, all) {
                    return i === 1 && lines.length > 2 ? l + '…' : l;
                });
            };

            const textColor = '#344767';
            const gridColor = 'rgba(52, 71, 103, 0.12)';
            const font = { family: 'Open Sans', size: 12 };

            const chart = new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: rows.map(function (r) { return splitTitle(r.title, 22).concat([r.date_label]); }),
                    datasets: [
                        {
                            type: 'bar',
                            label: 'Ingresos (USD)',
                            data: rows.map(function (r) { return r.revenue; }),
                            backgroundColor: 'rgba(251, 99, 64, 0.85)',
                            borderColor: '#fb6340',
                            borderWidth: 1,
                            borderRadius: 4,
                            maxBarThickness: 48,
                            yAxisID: 'y',
                            order: 2
                        },
                        {
                            type: 'line',
                            label: 'Boletos pagados',
                            data: rows.map(function (r) { return r.paid_tickets; }),
                            borderColor: '#1171ef',
                            backgroundColor: '#1171ef',
                            showLine: false,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointStyle: 'circle',
                            yAxisID: 'y1',
                            order: 1
                        },
                        {
                            type: 'line',
                            label: 'Boletos gratis',
                            data: rows.map(function (r) { return r.free_tickets; }),
                            borderColor: '#2dce89',
                            backgroundColor: '#2dce89',
                            showLine: false,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointStyle: 'rectRot',
                            yAxisID: 'y1',
                            order: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        title: {
                            display: true,
                            text: rangeLabel,
                            color: textColor,
                            font: { family: 'Open Sans', size: 15, weight: 'bold' },
                            padding: { bottom: 12 }
                        },
                        legend: {
                            display: true,
                            position: 'bottom',
                            // Chart.js sorts the legend by draw "order"; keep dataset order (Ingresos first).
                            labels: {
                                color: textColor,
                                font: font,
                                usePointStyle: true,
                                padding: 16,
                                sort: function (a, b) { return a.datasetIndex - b.datasetIndex; }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(33, 37, 41, 0.92)',
                            titleFont: { family: 'Open Sans', size: 13, weight: 'bold' },
                            bodyFont: font,
                            padding: 10,
                            callbacks: {
                                title: function (items) {
                                    const r = rows[items[0].dataIndex];
                                    return [r.title, r.date_label];
                                },
                                label: function (item) {
                                    const value = item.parsed.y;
                                    return ' ' + item.dataset.label + ': ' + (item.dataset.yAxisID === 'y' ? money.format(value) : integer.format(value));
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: textColor, font: font, autoSkip: false, maxRotation: 0 },
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            title: { display: true, text: 'Ingresos (USD)', color: textColor, font: { family: 'Open Sans', size: 12, weight: 'bold' } },
                            ticks: { color: textColor, font: font, callback: function (value) { return money.format(value); } },
                            grid: { color: gridColor }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: { display: true, text: 'Boletos', color: textColor, font: { family: 'Open Sans', size: 12, weight: 'bold' } },
                            ticks: { color: textColor, font: font, precision: 0, callback: function (value) { return Number.isInteger(value) ? integer.format(value) : ''; } },
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });

            const button = document.getElementById('btnDownloadChart');
            if (button) {
                button.addEventListener('click', function () {
                    // Copy onto a white canvas so the PNG is not transparent.
                    const source = chart.canvas;
                    const out = document.createElement('canvas');
                    out.width = source.width;
                    out.height = source.height;
                    const ctx = out.getContext('2d');
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, out.width, out.height);
                    ctx.drawImage(source, 0, 0);

                    const link = document.createElement('a');
                    link.href = out.toDataURL('image/png');
                    link.download = fileName;
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                });
            }
        });
    </script>
    @endif
@endpush
