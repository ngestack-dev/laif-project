@extends('layouts.app')

@section('content')
    <style>
        .pt-90 {
            padding-top: 90px !important;
        }

        .pr-6px {
            padding-right: 6px;
            text-transform: uppercase;
        }

        .my-account .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 40px;
            border-bottom: 1px solid;
            padding-bottom: 13px;
        }

        .my-account .wg-box {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            padding: 24px;
            flex-direction: column;
            gap: 24px;
            border-radius: 12px;
            background: var(--White);
            box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
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

        .table-transaction>tbody>tr:nth-of-type(odd) {
            --bs-table-accent-bg: #fff !important;

        }

        .table-transaction th,
        .table-transaction td {
            padding: 0.625rem 1.5rem .25rem !important;
            color: #000 !important;
        }

        .table> :not(caption)>tr>th {
            padding: 0.625rem 1.5rem .25rem !important;
            background-color: #6a6e51 !important;
        }

        .table-bordered>:not(caption)>*>* {
            border-width: inherit;
            line-height: 32px;
            font-size: 14px;
            border: 1px solid #e1e1e1;
            vertical-align: middle;
        }

        .table-striped .image {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            border-radius: 10px;
            overflow: hidden;
        }

        .table-striped td:nth-child(1) {
            min-width: 250px;
            padding-bottom: 7px;
        }

        .pname {
            display: flex;
            gap: 13px;
        }

        .table-bordered> :not(caption)>tr>th,
        .table-bordered> :not(caption)>tr>td {
            border-width: 1px 1px;
            border-color: #6a6e51;
        }


        .review-star {
            width: 20px;
            height: 20px;
            fill: gray;
            cursor: pointer;
            transition: fill 0.2s ease;
        }

        .review-star.selected {
            fill: gold;
        }

        #submit-rating {
            width: auto;
            /* Lebar otomatis mengikuti teks */
            display: inline-block;
            /* Pastikan tombol hanya selebar teks */
        }
    </style>

    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Orders Details</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>

                <div class="col-lg-10">
                    <div class="wg-box">
                        <div class="flex items-center justify-between gap10 flex-wrap">
                            <div class="row">
                                <div class="col text-left">
                                    <h5>Ordered Details</h5>
                                </div>
                                @if ($order->status == 'ordered')
                                    <div class="col text-center">
                                        <form action="{{ route('user.order.cancel', ['order_id' => $order->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger cancel">Cancel
                                                Order</button>
                                        </form>
                                    </div>
                                @elseif ($order->status == 'delivered')
                                    <div class="col text-center">
                                        <form action="{{ route('user.order.received', ['order_id' => $order->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success received">Order
                                                Received</button>
                                        </form>
                                    </div>
                                @endif
                                <div class="col text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('user.orders') }}">Back</a>
                                </div>
                            </div>
                        </div>
                        @if ($order->status == 'received')
                            {{-- Variabel flag untuk cek jika ada produk yang perlu dirating --}}
                            <h5 class="text-center mt-3">Rate This Product</h5>

                            @php
                                $isRatingAvailable = false;
                            @endphp
                            @if ($isRatingAvailable)
                                @foreach ($orderItems as $item)
                                    @if (!in_array($item->product_id, $ratedProducts))
                                        {{-- Set flag menjadi true jika produk ini belum dirating --}}
                                        @php
                                            $isRatingAvailable = true;
                                        @endphp
                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                        <div class="pname">
                                            <div class="image">
                                                <img src="{{ asset('uploads/products/thumbnails/') }}/{{ $item->product->image }}"
                                                    alt="{{ $item->product->name }}" class="image"
                                                    style="width: 50px; height: 50px;">
                                            </div>
                                            <div class="name">
                                                <a href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}"
                                                    target="_blank" class="body-title-2">{{ $item->product->name }}</a>
                                            </div>
                                        </div>

                                        <div class="reviews-group d-flex" data-item-id="{{ $item->product_id }}">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="review-star" data-star="{{ $i }}" viewBox="0 0 9 9"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_star" />
                                                </svg>
                                            @endfor
                                        </div>
                                    @endif
                                @endforeach

                                {{-- Tampilkan tombol submit jika ada produk yang perlu dirating --}}
                                @if ($isRatingAvailable)
                                    <button id="submit-rating" class="btn btn-sm btn-warning mt-2">Submit Rating</button>
                                @endif
                            @endif
                        @endif
                        <div class="table-responsive">
                            @if (Session::has('status'))
                                <p class="alert alert-success">{{ Session::get('status') }}></p>
                            @endif
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order No</th>
                                    <td>{{ $order->id }}</td>
                                    <th>Phone Number</th>
                                    <td>{{ $order->phone }}</td>
                                    <th>Zip Code</th>
                                    <td>{{ $order->zip_code }}</td>
                                </tr>
                                <tr>
                                    <th>Order Date</th>
                                    <td>{{ $order->created_at }}</td>
                                    <th>Delivered Date</th>
                                    <td>{{ $order->delivered_date }}</td>
                                    <th>Canceled Date</th>
                                    <td>{{ $order->canceled_date }}</td>
                                </tr>
                                <tr>
                                    <th>Order Status</th>
                                    <td colspan="3">
                                        @if ($order->status == 'delivered')
                                            <span class="badge bg-info">Delivered</span>
                                        @elseif ($order->status == 'canceled')
                                            <span class="badge bg-danger">Canceled</span>
                                        @elseif ($order->status == 'received')
                                            <span class="badge bg-success">Received</span>
                                        @else
                                            <span class="badge bg-warning">Processed</span>
                                        @endif
                                    </td>
                                    <th>Received Date</th>
                                    <td>{{ $order->received_date }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="wg-box">
                        <div class="flex items-center justify-between gap10 flex-wrap">
                            <div class="wg-filter flex-grow">
                                <h5>Ordered Items</h5>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">SKU</th>
                                        {{-- <th class="text-center">Category</th>
                                <th class="text-center">Brand</th> --}}
                                        {{-- <th class="text-center">Options</th> --}}
                                        <th class="text-center">Return Status</th>
                                        {{-- <th class="text-center">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        <tr>
                                            <td class="pname">
                                                <div class="image">
                                                    <img src="{{ asset('uploads/products/thumbnails/') }}/{{ $item->product->image }}"
                                                        alt="{{ $item->product->name }}" class="image">
                                                </div>
                                                <div class="name">
                                                    <a href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}"
                                                        target="_blank" class="body-title-2">{{ $item->product->name }}</a>
                                                </div>
                                            </td>
                                            <td class="text-center">${{ $item->price }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-center">{{ $item->product->SKU }}</td>
                                            {{-- <td class="text-center">Category1</td> --}}
                                            {{-- <td class="text-center">Brand1</td> --}}
                                            {{-- <td class="text-center">{{ $item->options }}</td>   --}}
                                            <td class="text-center">{{ $item->rstatus == 0 ? 'No' : 'Yes' }}</td>
                                            {{-- <td class="text-center">
                                                <div class="list-icon-function view-icon">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </div>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divider"></div>
                        <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                            {{ $orderItems->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                    <div class="wg-box mt-5">
                        <h5>Shipping Address</h5>
                        <div class="my-account__address-item col-md-6">
                            <div class="my-account__address-item__detail">
                                <p>{{ $order->name }}</p>
                                <p>{{ $order->address }}</p>
                                <p>{{ $order->city }}, {{ $order->province }}</p>
                                {{-- <p>GHT, </p> --}}
                                {{-- <p>AAA</p> --}}
                                <p>{{ $order->zip_code }}</p>
                                <br>
                                <p>Phone Number : {{ $order->phone }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="wg-box mt-5">
                        <h5>Transactions</h5>
                        <table class="table table-striped table-bordered table-transaction">
                            <tbody>
                                <tr>
                                    <th>Subtotal</th>
                                    <td>{{ $order->subtotal }}</td>
                                    <th>Tax</th>
                                    <td>{{ $order->tax }}</td>
                                    <th>Discount</th>
                                    <td>{{ $order->discount }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>{{ $order->total }}</td>
                                    <th>Payment Mode</th>
                                    <td class="text-uppercase">{{ $transaction->mode }}</td>
                                    <th>Status</th>
                                    <td>
                                        @if ($transaction->status == 'success')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($transaction->status == 'declined')
                                            <span class="badge bg-danger">Declined</span>
                                        @elseif ($transaction->status == 'refunded')
                                            <span class="badge bg-secondary">Refunded</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.cancel').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "You want to cancel this order?",
                    type: "warning",
                    buttons: ["Cancel", "Yes"],
                    confirmButtonColor: "#dc3545",
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    <script>
        $(function() {
            $('.received').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "You already received this order?",
                    type: "warning",
                    buttons: ["Cancel", "Yes"],
                    receivedButtonColor: "#6ee84f",
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reviewGroups = document.querySelectorAll('.reviews-group');

            reviewGroups.forEach(group => {
                const stars = group.querySelectorAll('.review-star');
                let selectedRating = 0;

                stars.forEach(star => {
                    // Event listener untuk hover efek
                    star.addEventListener('mouseenter', () => highlightStars(group, star.dataset
                        .star));
                    star.addEventListener('mouseleave', () => highlightStars(group,
                        selectedRating));

                    // Event listener untuk klik (mengatur nilai rating)
                    star.addEventListener('click', () => {
                        selectedRating = star.dataset.star;
                        console.log(selectedRating);
                        highlightStars(group, selectedRating);
                    });
                });

                // Fungsi untuk memberi highlight pada bintang
                function highlightStars(group, rating) {
                    const starsInGroup = group.querySelectorAll('.review-star');
                    starsInGroup.forEach(star => {
                        if (star.dataset.star <= rating) {
                            star.classList.add('selected');
                        } else {
                            star.classList.remove('selected');
                        }
                    });
                }
            });

            // Submit rating
            document.getElementById('submit-rating').addEventListener('click', function() {
                let ratingsToSubmit = [];

                // Mengumpulkan rating dari semua produk yang di-rating
                document.querySelectorAll('.reviews-group').forEach(group => {
                    const selectedStar = group.querySelector('.review-star.selected');
                    console.log(selectedStar)
                    if (selectedStar) {
                        const productId = group.getAttribute('data-item-id');
                        const rating = selectedStar.dataset.star;
                        console.log(rating);

                        ratingsToSubmit.push({
                            product_id: productId,
                            stars: rating
                        });
                    }
                });

                if (ratingsToSubmit.length > 0) {
                    // Kirim rating yang telah dipilih ke server
                    fetch("{{ route('user.rating.product') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ratings: ratingsToSubmit
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => alert(data.message))
                        .catch(error => console.error(error));
                } else {
                    alert('Please select ratings for the products.');
                }
            });
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reviewGroups = document.querySelectorAll('.reviews-group');

            reviewGroups.forEach(group => {
                const stars = group.querySelectorAll('.review-star');
                let selectedRating = 0;

                // Simpan nilai rating di group
                group.dataset.selectedRating = selectedRating;

                stars.forEach(star => {
                    // Event listener untuk hover efek
                    star.addEventListener('mouseenter', () => highlightStars(group, star.dataset
                        .star));
                    star.addEventListener('mouseleave', () => highlightStars(group, group.dataset
                        .selectedRating));

                    // Event listener untuk klik (mengatur nilai rating)
                    star.addEventListener('click', () => {
                        selectedRating = star.dataset.star;
                        group.dataset.selectedRating =
                            selectedRating; // Update rating ke dataset
                        console.log(selectedRating);
                        highlightStars(group, selectedRating);
                    });
                });

                // Fungsi untuk memberi highlight pada bintang
                function highlightStars(group, rating) {
                    const starsInGroup = group.querySelectorAll('.review-star');
                    starsInGroup.forEach(star => {
                        if (star.dataset.star <= rating) {
                            star.classList.add('selected');
                        } else {
                            star.classList.remove('selected');
                        }
                    });
                }
            });

            // Submit rating
            document.getElementById('submit-rating').addEventListener('click', function() {
                let ratingsToSubmit = [];

                // Mengumpulkan rating dari semua produk yang di-rating
                reviewGroups.forEach(group => {
                    const productId = group.getAttribute('data-item-id');
                    const selectedRating = group.dataset.selectedRating;

                    if (selectedRating > 0) { // Cek jika ada rating yang dipilih
                        ratingsToSubmit.push({
                            product_id: productId,
                            stars: selectedRating
                        });
                    }
                });

                if (ratingsToSubmit.length > 0) {
                    // Kirim rating yang telah dipilih ke server
                    fetch("{{ route('user.rating.product') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ratings: ratingsToSubmit
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => alert(data.message))
                        .catch(error => console.error(error));
                } else {
                    alert('Please select ratings for the products.');
                }
            });
        });
    </script>
@endpush
