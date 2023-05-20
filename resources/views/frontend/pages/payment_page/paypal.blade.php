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
        <section class="content-wrapper mt-0 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="page-title h2 text-center uppercase mt-1 mb-5">{{ $payment_method_name }}
                            <small>
                                @if(env('CURRENCY_FORMAT')=='suffix')
                                    ( {{ number_format((float)$totalAmount, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL') )
                                @else
                                    ( @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float) $totalAmount, env('FORMAT_NUMBER'), '.', '') }} )
                                @endif
                            </small>
                        </h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="container-fluid" id="errorMessage"></div>

                                <form action="{{route('payment.process')}}" method="post" id="paypalPaymentForm">
                                    <input type="hidden" name="requestData" value="{{ $requestData }}">
                                    <input type="hidden" name="totalAmount" value="{{ $totalAmount }}">

                                    <div id="paypal-button-container"></div>

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

    <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_CLIENT_ID')}}&currency=USD" data-namespace="paypal_sdk"></script>

    <script type="text/javascript">
        $(function(){
            let targetURL = "{{ url('/payment/paypal/pay/confirm')}}";
            let cancelURL = "{{ url('/payment/paypal/pay/cancel')}}";
            let redirectURL = "{{ url('/payment_success')}}";
            let redirectURLAfterCancel = "{{ url('/')}}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Render the PayPal button into #paypal-button-container
            paypal_sdk.Buttons({
                // Call your server to set up the transaction
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: $('input[name="totalAmount"]').val(),
                                currency_code: "USD",
                            }
                        }]
                    });
                },

                // Call your server to finalize the transaction
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        if (details.status=="COMPLETED") {
                            $.post({
                                url: targetURL,
                                data: $('#paypalPaymentForm').serialize(),
                                success: function (response) {
                                    console.log(response);
                                    if (response.errors) {
                                        let html = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        ${response.errors}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>`;
                                        $('#errorMessage').html(html);
                                    }else if(response.success){
                                        window.location.href = redirectURL;
                                    }
                                }
                            });
                        }
                    });
                }
            }).render('#paypal-button-container');

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
