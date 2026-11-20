@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Reenviar emails de compra'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div id="alert">
            @include('components.alert')
        </div>
        <div class="card-body p-3">
            <div class="card-header pb-0">
                <div class="d-flex align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-0">Reenviar emails de compra</h4>
                        <p class="text-sm mb-0">{{ $event->title }} &middot; {{ $event->date_time_start }}</p>
                    </div>
                    <a href="{{ route('event.index') }}" class="btn btn-outline-secondary btn-sm ms-auto mb-0 me-2">Volver a eventos</a>
                    <button type="submit" form="frmResendEmails" class="btn btn-primary btn-sm mb-0" id="btnResend" disabled>
                        Reenviar a seleccionadas (<span id="selectedCount">0</span>)
                    </button>
                </div>
                <p class="text-xs text-secondary mt-2 mb-0">
                    Se envía el mismo correo de compra con el PDF de los tickets. Si un comprador tiene varias órdenes seleccionadas, recibe un solo correo con todos sus tickets.
                    La selección aplica a las órdenes de esta página (máximo {{ $orders->perPage() }}).
                </p>
                <form method="GET" action="{{ route('event.resend-emails', $event->id) }}" class="d-flex align-items-center mt-3" role="search" style="max-width: 520px;">
                    <input type="search" name="search" class="form-control form-control-sm me-2" maxlength="100" value="{{ $search }}"
                        placeholder="Buscar por folio, código, comprador o email" aria-label="Buscar órdenes">
                    <button type="submit" class="btn btn-dark btn-sm mb-0 me-2">Buscar</button>
                    @if ($search !== '')
                        <a href="{{ route('event.resend-emails', $event->id) }}" class="btn btn-outline-secondary btn-sm mb-0">Limpiar</a>
                    @endif
                </form>
            </div>
            <div class="card-body px-0 pt-3 pb-2">
                <form method="POST" action="{{ route('event.resend-emails.send', $event->id) }}" id="frmResendEmails">
                    @csrf
                    <div class="table-responsive p-0">
                        <table class="table align-items-center justify-content-center mb-0" id="resendOrders">
                            <thead>
                                <tr>
                                    <th style="padding-left: 24px; width: 40px;">
                                        <input class="form-check-input" type="checkbox" id="selectAll" title="Seleccionar todas las visibles" aria-label="Seleccionar todas las órdenes visibles" @disabled($orders->isEmpty())>
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Folio</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Comprador</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ticket</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td style="padding-left: 24px">
                                            <input class="form-check-input order-checkbox" type="checkbox" name="order_ids[]" value="{{ $order->id }}" id="order-{{ $order->id }}"
                                                aria-label="Seleccionar orden {{ $order->id }}" @checked(in_array((string) $order->id, array_map('strval', (array) old('order_ids', [])), true))>
                                        </td>
                                        <td>
                                            <label for="order-{{ $order->id }}" class="text-sm font-weight-bold mb-0">#{{ $order->id }}</label>
                                            <p class="text-xs text-secondary mb-0">{{ $order->code }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $order->name_buyer . ' ' . $order->last_name_buyer }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $order->email_buyer }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $order->ticket->title ?? '' }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm {{ $order->stripe_data ? 'bg-gradient-success' : 'bg-gradient-info' }}">{{ $order->stripe_data ? 'Pagada' : 'Gratis' }}</span>
                                            <span class="badge badge-sm {{ $order->assist ? 'bg-gradient-dark' : 'bg-gradient-secondary' }}">{{ $order->assist ? 'Asistió' : 'Sin asistencia' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-sm mb-0">{{ $search !== '' ? 'Ninguna orden coincide con la búsqueda.' : 'Este evento no tiene órdenes.' }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
                @if ($orders->total() > 0)
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between px-4 pt-3">
                        <p class="text-sm text-secondary mb-2 mb-md-0">
                            Mostrando {{ $orders->firstItem() }} a {{ $orders->lastItem() }} de {{ $orders->total() }} órdenes
                        </p>
                        {{ $orders->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('frmResendEmails');
        const selectAll = document.getElementById('selectAll');
        const button = document.getElementById('btnResend');
        const counter = document.getElementById('selectedCount');
        const checkboxes = Array.from(document.querySelectorAll('.order-checkbox'));

        if (!form || !selectAll || !button || !counter) {
            return;
        }

        function refresh() {
            const selected = checkboxes.filter(cb => cb.checked).length;
            counter.textContent = selected;
            button.disabled = selected === 0;
            selectAll.checked = checkboxes.length > 0 && selected === checkboxes.length;
            selectAll.indeterminate = selected > 0 && selected < checkboxes.length;
        }

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => { cb.checked = selectAll.checked; });
            refresh();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', refresh));

        form.addEventListener('submit', function (e) {
            const selected = checkboxes.filter(cb => cb.checked).length;
            if (selected === 0 || !confirm('Se reenviará el correo de compra de ' + selected + ' orden(es) a sus compradores. ¿Continuar?')) {
                e.preventDefault();
                return;
            }
            button.disabled = true;
        });

        refresh();
    });
</script>
@endpush
