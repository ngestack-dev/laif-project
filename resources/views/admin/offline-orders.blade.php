@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Offline Orders</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Offline Orders</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" action="{{ route('admin.search.offline.order') }}" method="GET">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="query"
                                    tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.add.offline.order') }}"><i
                            class="icon-plus"></i>Add
                        Order</a>
                </div>
                @if (session('success'))
                    <div class="alert alert-success fs-3">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px">OrderNo</th>
                                    <th class="text-center">Admin/Super-Admin</th>
                                    <th class="text-center">Total Items</th>
                                    <th class="text-center">Total</th>

                                    <th class="text-center">Status</th>
                                    <th class="text-center">Timestamp</th>
                                    <th class="text-center">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="text-center">
                                            @if (auth()->user()->hasRole('super-admin'))
                                                {{ $order->id }}
                                            @else
                                                {{ $order->id }}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $order->admin->position }}/{{ $order->admin->name }}</td>
                                        <td class="text-center">{{ $order->orderItems->sum('quantity') }}</td>
                                        <td class="text-center">Rp{{ number_format($order->total, 3, '.', '.') }}</td>
                                        <td class="text-center">{{ $order->status }}</td>
                                        <td class="text-center">{{ $order->created_at->format('d M Y H:i') }}</td>
                                        <td class="text-center">
                                            <a
                                                href="{{ route('admin.offline.order.details', ['order_id' => $order->id]) }}">
                                                <div class="list-icon-function view-icon">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="8">
                                            Order not found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle d-flex align-items-center fs-5" type="button"
                            id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-download me-2" aria-hidden="true" style="font-size: 20px;"></i>
                            Export Data
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li>
                                <a class="dropdown-item fs-4" href="{{ route('export.offline.orders.xlsx') }}">Export to
                                    Excel</a>
                            </li>
                            <li>
                                <a class="dropdown-item fs-4" href="{{ route('export.offline.orders.csv') }}">Export to
                                    CSV</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
