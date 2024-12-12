@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">My Account</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__dashboard">
                        <p class="fs-3">Hello, <strong>{{ Auth::user()->name }}</strong>!</p>
                        <p class="fs-4 d-flex align-items-center">
                            Thank you for becoming a member of
                            <img src="{{ asset('assets/images/logo/PNG/Master Logo Laif Essentials-01.png') }}"
                                alt="" width="50" height="50">
                        </p>
                        <p class="fs-4">Here you can manage your account dashboard where you can view your <a class="unerline-link"
                            href="{{ route('user.orders') }}">recent
                            orders</a>, change your <a class="unerline-link" href="{{ route('user.address') }}">shipping
                            address</a>, and <a class="unerline-link" href="{{ route('user.details') }}">edit your
                            account
                            details.</a></p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
