@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">

        <div class="main-content-wrap">
            <div class="tf-section-2 mb-30">
                <div class="flex gap20 flex-wrap-mobile">
                    <div class="w-100">
                        @role('super-admin')
                            <div class="wg-chart-default mb-20">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap14">
                                        <div class="image ic-bg">
                                            <i class="icon-shopping-bag"></i>
                                        </div>
                                        <div>
                                            <div class="body-text mb-2">Total Complete Orders</div>
                                            <h4>{{ $ordersCount }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="wg-chart-default mb-20">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap14">
                                        <div class="image ic-bg">
                                            <i class="icon-dollar-sign"></i>
                                        </div>
                                        <div>
                                            <div class="body-text mb-2">Total Amount</div>
                                            <h4>Rp{{ number_format($totalOrdersAmount, 3, '.', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endrole
                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Total Offline Orders</div>
                                        <h4>{{ $offlineOrdersCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Offline Orders Amount</div>
                                        <h4>Rp{{ number_format($offlineOrdersAmount, 3, '.', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="wg-box">
                    <div class="flex items-center justify-between">
                        <h5>Earnings revenue</h5>
                    </div>
                    <div class="flex flex-wrap gap40">
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t1"></div>
                                    <div class="text-tiny">Online Orders</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>Rp{{ number_format($totalOrdersAmount, 3, '.', '.') }}</h4>
                                {{-- <div class="box-icon-trending up">
                                    <i class="icon-trending-up"></i>
                                    <div class="body-title number">0.56%</div>
                                </div> --}}
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t3  "></div>
                                    <div class="text-tiny">Offline Order</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>Rp{{ number_format($offlineOrdersAmount, 3, '.', '.') }}</h4>
                                {{-- <div class="box-icon-trending up">
                                    <i class="icon-trending-up"></i>
                                    <div class="body-title number"></div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div id="line-chart-8" style="height: 400px; width: 100%;"></div>
                </div>

            </div>
            <div class="tf-section mb-30">
                <div class="wg-box">
                    <div class="flex items-center justify-between">
                        <h5>Recent orders</h5>
                        <div class="dropdown default">
                            <a class="btn btn-secondary dropdown-toggle" href="{{ route('admin.orders') }}">
                                <span class="view-all">View all</span>
                            </a>
                        </div>
                    </div>
                    @role('super-admin')
                        <div class="wg-table table-all-user">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px">OrderNo</th>
                                            <th>Name</th>
                                            <th class="text-center" style="width: 150px">Phone</th>
                                            <th class="text-center">Total Items</th>
                                            <th class="text-center">Subtotal</th>
                                            <th class="text-center">Tax</th>
                                            <th class="text-center">Total</th>

                                            <th class="text-center">Status</th>
                                            <th class="text-center">Order Date</th>
                                            {{-- <th class="text-center">Delivered On</th> --}}
                                            <th class="text-center">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{ $recentOrder->id }}</td>
                                            <td class="text-center">{{ $recentOrder->name }}</td>
                                            <td class="text-center">{{ $recentOrder->phone }}</td>
                                            <td class="text-center">{{ $recentOrder->orderItems->count() }}</td>
                                            <td class="text-center">Rp{{ $recentOrder->subtotal }}0</td>
                                            <td class="text-center">Rp{{ $recentOrder->tax }}0</td>
                                            <td class="text-center">Rp{{ $recentOrder->total }}0</td>
                                            <td class="text-center">{{ $recentOrder->status }}</td>
                                            <td class="text-center">{{ $recentOrder->created_at->format('d M Y H:i') }}</td>
                                            {{-- <td>{{ $recentOrder->delivered_date }}</td> --}}
                                            <td class="text-center">
                                                <a href="{{ route('admin.order.details', ['order_id' => $recentOrder->id]) }}">
                                                    <div class="list-icon-function view-icon">
                                                        <div class="item eye">
                                                            <i class="icon-eye"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endrole
                    @role('admin')
                        <div class="wg-table table-all-user">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:70px">OrderNo</th>
                                            <th class="text-center">Total Items</th>
                                            <th class="text-center">Subtotal</th>
                                            <th class="text-center">Tax</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Timestamp</th>
                                            <th class="text-center">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($recentOfflineOrder)
                                            <tr>
                                                <td class="text-center">{{ $recentOfflineOrder->id }}</td>
                                                <td class="text-center">{{ $recentOfflineOrder->orderItems->count() }}</td>
                                                <td class="text-center">Rp{{ $recentOfflineOrder->subtotal }}0</td>
                                                <td class="text-center">Rp{{ $recentOfflineOrder->tax }}0</td>
                                                <td class="text-center">Rp{{ $recentOfflineOrder->total }}0</td>
                                                <td class="text-center">{{ $recentOfflineOrder->status }}</td>
                                                <td class="text-center">
                                                    {{ $recentOfflineOrder->created_at->format('d M Y H:i') }}</td>
                                                <td class="text-center">
                                                    <a
                                                        href="{{ route('admin.offline.order.details', ['order_id' => $recentOfflineOrder->id]) }}">
                                                        <div class="list-icon-function view-icon">
                                                            <div class="item eye">
                                                                <i class="icon-eye"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="text-center" colspan="8">Order not found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
@endsection
