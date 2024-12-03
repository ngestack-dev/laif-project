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
            {{-- @if (session($products))
                <div>
                    <div class="alert alert-danger fs-3">
                        {{ session($products) }}
                    </div>
                </div>
            @endif --}}
            <form method="post" action="{{ route('admin.store.offline.order') }}">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                    @csrf
                    @foreach ($products as $product)
                        <div class="col mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body p-3">
                                    <div class="product-card">
                                        <div class="product-card__img-wrapper text-center mb-3">
                                            <a
                                                href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                                                <img loading="lazy"
                                                    src="{{ asset('uploads/products') }}/{{ $product->image }}"
                                                    class="img-fluid" alt="{{ $product->name }}"
                                                    style="max-height: 200px; object-fit: contain;">
                                            </a>
                                        </div>
                                        <div class="product-card__price text-center mb-3">
                                            <span class="fs-3">
                                                Rp{{ $product->regular_price }}0
                                            </span>
                                        </div>

                                        <input type="hidden" name="products[{{ $product->id }}][id]"
                                            value="{{ $product->id }}">
                                        <input type="hidden" name="products[{{ $product->id }}][name]"
                                            value="{{ $product->name }}">
                                        <input type="hidden" name="products[{{ $product->id }}][price]"
                                            value="{{ $product->sale_price ?: $product->regular_price }}">


                                        <div class="input-group mb-2">
                                            <input type="number" name="products[{{ $product->id }}][quantity]"
                                                value="0" min="0" class="form-control text-center">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="tf-button style-1 w208">Submit</button>
                </div>
            </form>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection
