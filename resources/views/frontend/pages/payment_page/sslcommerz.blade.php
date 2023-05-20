@extends('frontend.layouts.master')
@section('frontend_content')
    <div class="container">

        <!--Breadcrumb Area start-->
        <div class="breadcrumb-section">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <ul>
                            <li><a href="{{ route('cartpro.home') }}">@lang('file.Home')</a></li>
                            <li><a href="/">@lang('file.Checkout')</a></li>
                            <li class="active">@lang('file.Payment')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--Breadcrumb Area ends-->

        <!-- Content Wrapper -->
        <section class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="page-title h2 text-center uppercase mt-1 mb-5">{{ $payment_method_name }}</h1>
                    </div>
                </div>
            </div>
        </section>

        <div class="row mb-4">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('payment.pay.confirm','sslcommerz')}}" method="post" id="razorpayPaymentForm">
                            @csrf

                            <input type="hidden" name="requestData" value="{{ $requestData }}">
                            <input type="hidden" name="totalAmount" value="{{ $totalAmount }}">

                            <div class="d-grid gap-2 mx-auto">
                                <button type="submit" class="btn btn-outline-success">
                                    Pay Now
                                    <small>
                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                            ( {{ number_format((float)$totalAmount, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL'))
                                        @else
                                            ( @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float) $totalAmount, env('FORMAT_NUMBER'), '.', '') }})
                                        @endif
                                    </small>
                                </button>
                            </div>
                            <div class="mt-3 d-grid gap-2 mx-auto">
                                <button type="button" id="payCancelBtn" class="btn btn-outline-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
@endpush
