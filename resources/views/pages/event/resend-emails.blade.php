@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <style>
        /* The checkboxes are not wrapped in .form-check, where Argon draws their border and tick. */
        #resendOrders .form-check-input[type=checkbox] {
            border: 1px solid #cbd3da;
            cursor: pointer;
        }
        #resendOrders .form-check-input[type=checkbox]:checked {
            border-color: transparent;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M5 10.5l3.5 3.5 6.5-7'/%3e%3c/svg%3e"), linear-gradient(310deg, #fb6340 0%, #f56036 100%);
            background-size: 100% 100%, 100% 100%;
        }
        #resendOrders .form-check-input[type=checkbox]:indeterminate {
            border-color: #fb6340;
        }
        #resendOrders .form-check-input[type=checkbox]:focus-visible {
            box-shadow: 0 0 0 2px rgba(251, 99, 64, 0.35);
        }
        #resendOrders td.estado-cell {
            white-space: normal;
            min-width: 190px;
        }
    </style>
    @include('layouts.navbars.auth.topnav', ['title' => 'Resend purchase emails'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div id="alert">
            @include('components.alert')
        </div>
        <div class="card-body p-3">
            <div class="card-header pb-0">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="mb-0">Resend purchase emails</h4>
                        <p class="text-sm mb-0">{{ $event->title }} &middot; {{ $event->date_time_start }}</p>
                    </div>
                    <div class="ms-auto d-flex align-items-center flex-wrap gap-2">
                        <div class="input-group input-group-sm" style="width: 280px;">
                            <span class="input-group-text bg-white"><i class="fas fa-at text-xs"></i></span>
                            <input type="email" name="custom_email" form="frmResendEmails" id="customEmailInput"
                                class="form-control form-control-sm"
                                placeholder="Send to another email (optional)"
                                title="If left empty, it will be sent to the buyer's original email"
                                value="{{ old('custom_email') }}">
                        </div>
                        <a href="{{ route('event.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Back to events</a>
                        <button type="submit" form="frmResendEmails" class="btn btn-primary btn-sm mb-0" id="btnResend" disabled>
                            Resend to selected (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                </div>
                <p class="text-xs text-secondary mt-2 mb-0">
                    Sends the purchase email with the tickets PDF attached. If you enter an alternative email above, the selected orders will be sent to that address instead of the original buyer's email. If left blank, it will be sent to the original buyer.
                    Selection applies to orders on this page (maximum {{ $orders->perPage() }}).
                </p>
                <form method="GET" action="{{ route('event.resend-emails', $event->id) }}" class="d-flex align-items-center mt-3" role="search" style="max-width: 520px;">
                    <input type="search" name="search" class="form-control form-control-sm me-2" maxlength="100" value="{{ $search }}"
                        placeholder="Search by order #, code, buyer or email" aria-label="Search orders">
                    <button type="submit" class="btn btn-dark btn-sm mb-0 me-2">Search</button>
                    @if ($search !== '')
                        <a href="{{ route('event.resend-emails', $event->id) }}" class="btn btn-outline-secondary btn-sm mb-0">Clear</a>
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
                                        <input class="form-check-input" type="checkbox" id="selectAll" title="Select all visible" aria-label="Select all visible orders" @disabled($orders->isEmpty())>
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Order #</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Buyer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ticket</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="min-width: 190px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td style="padding-left: 24px">
                                            <input class="form-check-input order-checkbox" type="checkbox" name="order_ids[]" value="{{ $order->id }}" id="order-{{ $order->id }}"
                                                aria-label="Select order {{ $order->id }}" @checked(in_array((string) $order->id, array_map('strval', (array) old('order_ids', [])), true))>
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
                                        <td class="estado-cell">
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge badge-sm {{ $order->stripe_data ? 'bg-gradient-success' : 'bg-gradient-info' }}">{{ $order->stripe_data ? 'Paid' : 'Free' }}</span>
                                                <span class="badge badge-sm {{ $order->assist ? 'bg-gradient-dark' : 'bg-gradient-secondary' }}">{{ $order->assist ? 'Attended' : 'Not attended' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-sm mb-0">{{ $search !== '' ? 'No orders match the search criteria.' : 'This event has no orders.' }}</p>
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
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
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
        const customEmailInput = document.getElementById('customEmailInput');
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
            if (selected === 0) {
                e.preventDefault();
                return;
            }

            const targetEmail = customEmailInput ? customEmailInput.value.trim() : '';
            const confirmMsg = targetEmail !== ''
                ? 'The purchase email for ' + selected + ' order(s) will be resent to: ' + targetEmail + '. Continue?'
                : 'The purchase email for ' + selected + ' order(s) will be resent to their original buyers. Continue?';

            if (!confirm(confirmMsg)) {
                e.preventDefault();
                return;
            }
            button.disabled = true;
        });

        refresh();
    });
</script>
@endpush
