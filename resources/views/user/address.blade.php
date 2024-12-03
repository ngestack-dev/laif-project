@extends('layouts.app')

@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Addresses</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__address">
                        @if ($address)
                            <div class="row">
                                <div class="col-6">
                                    <p class="notice">The following addresses will be used on the checkout page by default.
                                    </p>
                                </div>
                            </div>
                            <div class="my-account__address-list row">
                                <h5>Shipping Address</h5>

                                <div class="my-account__address-item col-md-6">
                                    <div class="my-account__address-item__title">
                                        <h5>{{ Auth::user()->name }} <i class="fa fa-check-circle text-success"></i></h5>
                                        <a href="{{ route('user.address.edit', $address->id)}}">Edit</a>
                                    </div>
                                    <div class="my-account__address-item__detail">
                                        {{-- <p>Type : {{ $address->type}}</p><br> --}}
                                        <p>{{ $address->address }}</p>
                                        <p>{{ $address->city }}</p>
                                        <p>{{ $address->province }}</p>
                                        {{-- <p>Near Sun Temple</p> --}}
                                        <p>{{ $address->zip_code }}</p>
                                        <br>
                                        <p>Mobile : {{ $address->mobile }}</p>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @else
                            <div class="col-6">
                                <p class="notice">Add your shipping address now.
                                </p>
                            </div>
                            <form action="{{ route('user.address.add') }}" method="POST">
                                @csrf
                                <div class="row mt-5">
                                    <div class="col-md-6">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="name" required=""
                                                value="{{ Auth::user()->name }}" readonly>
                                            <label for="name">Name *</label>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="mobile" required=""
                                                value="{{ Auth::user()->mobile }}" readonly>
                                            <label for="mobile">Phone Number *</label>
                                            @error('mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mt-3 mb-3">
                                            <input type="text" class="form-control" name="province" required=""
                                                value="{{ old('province') }}">
                                            <label for="province">Province *</label>
                                            @error('province')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="city" required=""
                                                value="{{ old('city') }}">
                                            <label for="city">Subdistrict & City *</label>
                                            @error('city')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="zip_code" required=""
                                                value="{{ old('zip_code') }}">
                                            <label for="zip">Zip Code *</label>
                                            @error('zip_code')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="address" required=""
                                                value="{{ old('address') }}">
                                            <label for="address">Address *</label>
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="my-3">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
