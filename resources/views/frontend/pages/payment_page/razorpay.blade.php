@extends('frontend.layouts.master')
@section('frontend_content')

    <style>
        .razorpay-payment-button{
            display: none
        }
    </style>

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
        <section class="content-wrapper mt-0 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="page-title h2 text-center uppercase mt-1 mb-5">{{ $payment_method_name }}</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">

                                @if($message = Session::get('error'))
                                    <div class="d-flex justify-content-center">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>{{ $message }}</strong>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{route('payment.pay.confirm','razorpay')}}" method="post" id="razorpayPaymentForm">
                                    @csrf
                                    <input type="hidden" name="requestData" value="{{ $requestData }}">
                                    <input type="hidden" name="totalAmount" value="{{ $totalAmount }}">

                                    <script src="https://checkout.razorpay.com/v1/checkout.js"
                                        data-key="{{ env('RAZORPAY_KEY') }}"
                                        data-amount="{{ $totalAmount * 100 }}"
                                        data-buttontext="Pay Now {{ $totalAmount }} INR"
                                        data-name=""
                                        data-description="Razorpay"
                                        data-image=""
                                        data-prefill.name="name"
                                        data-prefill.email="email"
                                        data-theme.color="#ff7529">
                                    </script>

                                    <div class="mt-4 d-grid gap-2 mx-auto">
                                        <button type="submit" id="payNowBtn" class="btn btn-outline-success">
                                            Pay Now
                                            <small>
                                                @if(env('CURRENCY_FORMAT')=='suffix')
                                                    ( {{ number_format((float)$totalAmount, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL') INR)
                                                @else
                                                    ( @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float) $totalAmount, env('FORMAT_NUMBER'), '.', '') }} INR)
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
                    <div class="col-md-3"></div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(function(){
            let cancelURL = "{{ url('/payment/paypal/pay/cancel')}}";
            let redirectURLAfterCancel = "{{ url('/')}}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#payNowBtn").click(function(){
                $("#payNowBtn").text('Please wait...');
            });
            $("#payCancelBtn").click(function(){
                if (confirm('Are you sure to cancel ?')) {
                    $.ajax({
                        url: cancelURL,
                        type: 'POST',
                        data: {},
                        dataType: 'JSON',
                        success: function (data) {
                            window.location.href = redirectURLAfterCancel;
                        }
                    });
                }
            });
        });
    </script>
@endpush
