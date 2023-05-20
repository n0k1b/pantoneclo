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


                                <form class="mb-3 require-validation" id="stripePaymentForm" data-cc-on-file="false">
                                    @csrf
                                    <input type="hidden" name="requestData" value="{{ $requestData }}">
                                    <input type="hidden" id="stripeKey" value="{{env('STRIPE_KEY')}}">
                                    <input type="hidden" name="stripeToken">


                                    <div class='row'>
                                        <div class='col-md-12 form-group'>
                                            <input class='form-control' size='4' type='text' name="card_name" placeholder="@lang('file.Name on Card')" value="Test">
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group'>
                                            <input type="number" autocomplete='off' name="card_number" class='form-control card-number' placeholder="@lang('file.Card Number')" size='20' type='text' value="4242424242424242">
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 col-md-4 form-group cvc'>
                                            <input type="number" autocomplete='off' name="card-cvc" class='form-control card-cvc' size='4' type='text' placeholder="CVC (ex. 311)" value="311">
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration'>
                                            <input type="number" min="1" max="12" class='form-control card-expiry-month' name="card-expiry-month" size='2' type='text' placeholder="Expiration Month (MM)" value="12">
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration'>
                                            <input  type="number" class='form-control card-expiry-year' name="card-expiry-year" placeholder='Expiration Year (YYYY)' size='4' type='text' value="2025">
                                        </div>
                                    </div>
                                    <div class="mt-4 d-grid gap-2 mx-auto">
                                        <button type="submit" id="payNowBtn" class="btn btn-outline-success">Pay Now</button>
                                    </div>
                                    <div class="mt-3 d-grid gap-2 mx-auto">
                                        {{-- <button type="button" id="payCancelBtn" onclick="confirm('Are you sure to cancel ?')" --}}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript">
        $(function() {
            let targetURL = "{{ url('/payment/stripe/pay/confirm')}}";
            let cancelURL = "{{ url('/payment/stripe/pay/cancel')}}";
            let redirectURL = "{{ url('/payment_success')}}";
            let redirectURLAfterCancel = "{{ url('/')}}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



                var $form = $(".require-validation");
                $('form').bind('submit', function(e) {
                // $("#stripePaymentForm").on("submit",function(e){
                    e.preventDefault();
                    const stripePublishableKey = $('#stripeKey').val();
                    $('#payNowBtn').text('Processing...');
                    if (!$form.data('cc-on-file')) {
                        Stripe.setPublishableKey(stripePublishableKey);
                        Stripe.createToken({
                            number: $('.card-number').val(),
                            cvc: $('.card-cvc').val(),
                            exp_month: $('.card-expiry-month').val(),
                            exp_year: $('.card-expiry-year').val()
                        }, stripeResponseHandler);
                    }
                });

                function stripeResponseHandler(status, response) {
                    if (response.error) {
                        $('.error')
                            .removeClass('hide')
                            .find('.alert')
                            .text(response.error.message);
                    } else {
                        // token contains id, last4, and card type
                        var token = response['id'];
                        $('input[name="stripeToken"]').val(token);

                        $.ajax({
                            url: targetURL,
                            method: "POST",
                            data: new FormData(document.getElementById("stripePaymentForm")),
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function (response) {
                                $('#payNowBtn').text('Pay Now');
                                if (response.errors) {
                                    let html = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    ${response.errors}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>`;
                                    $('#errorMessage').html(html);
                                }else if(response.success){
                                    console.log(response);
                                    window.location.href = redirectURL;
                                }
                            }
                        })
                    }
                }

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
