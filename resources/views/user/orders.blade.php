@extends('layouts.app')

@section('content')
    <style>
        .table> :not(caption)>tr>th {
            padding: 0.625rem 1.5rem .625rem !important;
            background-color: #6a6341 !important;
        }

        .table>tr>td {
            padding: 0.625rem 1.5rem .625rem !important;
        }

        .table-bordered> :not(caption)>tr>th,
        .table-bordered> :not(caption)>tr>td {
            border-width: 1px 1px;
            border-color: #6a6e51;
        }

        .table> :not(caption)>tr>td {
            padding: .8rem 1rem !important;
        }

        .bg-success {
            background-color: #40c710 !important;
        }

        .bg-danger {
            background-color: #f44032 !important;
        }

        .bg-warning {
            background-color: #f5d700 !important;
            color: #000;
        }
    </style>
    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h4 class="page-title]\">Orders</h4>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>

                <div class="col-lg-10">
                    <div class="wg-table table-all-user">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>OrderNo</th>
                                        <th>Name</th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Items</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Tax</th>
                                        <th class="text-center">Total</th>

                                        <th class="text-center">Status</th>
                                        <th class="text-center">Order Date</th>
                                        <th class="text-center">Delivered On</th>
                                        <th class="text-center">Received On</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td class="text-center">{{ $order->id }}</td>
                                            <td class="text-center">{{ Auth::user()->name }}</td>
                                            <td class="text-center">
                                                {{ $order->phone }}</td>
                                            <td class="text-center">{{ $order->orderItems->sum('quantity') }}</td>
                                            <td class="text-center">Rp{{ number_format($order->subtotal, 3, '.', '.') }}
                                            </td>
                                            <td class="text-center">Rp{{ number_format($order->tax, 3, '.', '.') }}</td>
                                            <td class="text-center">Rp{{ number_format($order->total, 3, '.', '.') }}</td>

                                            <td class="text-center" style="text-transform: capitalize;">
                                                @if ($order->status === 'ordered')
                                                    processed
                                                @else
                                                    {{ $order->status }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $order->created_at->format('d M Y H:i') }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($order->delivered_date)->format('d M Y H:i') }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($order->received_date)->format('d M Y H:i') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('user.order.details', ['order_id' => $order->id]) }}">
                                                    <div class="list-icon-function view-icon">
                                                        <div class="item eye">
                                                            <i class="fa fa-eye"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center"><br>You don't have an order<br><br><a href="{{ route('shop.index') }}" class="btn btn-primary fs-8">Shop Now</a></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                </div>
        </section>
    </main>
@endsection
