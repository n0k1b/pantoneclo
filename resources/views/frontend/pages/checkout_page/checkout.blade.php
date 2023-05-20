@php
if (Session::has('currency_rate')){
    $CHANGE_CURRENCY_RATE = Session::get('currency_rate');
}else{
    $CHANGE_CURRENCY_RATE = 1;
    Session::put('currency_rate', $CHANGE_CURRENCY_RATE);
}

if (Session::has('currency_symbol')){
    $CHANGE_CURRENCY_SYMBOL = Session::get('currency_symbol');
}else{
    $CHANGE_CURRENCY_SYMBOL = env('DEFAULT_CURRENCY_SYMBOL');
    Session::put('currency_symbol',$CHANGE_CURRENCY_SYMBOL);
}
@endphp




@extends('frontend.layouts.master')
@section('extra_css')
@section('frontend_content')

<div class="container">

    <!--Breadcrumb Area start-->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul>
                        <li><a href="{{route('cartpro.home')}}">@lang('file.Home')</a></li>
                        <li class="active">@lang('file.Checkout')</li>
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
                    <h1 class="page-title h2 text-center uppercase mt-1 mb-5">@lang('file.Checkout')</h1>
                    @if (!Auth::check())
                        <div class="col-md-6 offset-md-3 col-sm-12 text-right mar-bot-20">
                            <div class="alert alert-secondary text-center res-box" role="alert">
                                <div class="alert-icon"><i class="ion-android-favorite-outline mr-2"></i> <span>@lang('file.Register Customer') ? </span>
                                    <a target="__blank" href="" class="semi-bold theme-color" data-bs-toggle="modal" data-bs-target="#exampleModal">@lang('file.Click here to login')</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>


                <!-- Login Modal -->
                @include('frontend.pages.checkout_page.login_modal')




                <!-- Alert Message -->
                <div class="d-flex justify-content-center d-none" id="alert_div">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div id="alert_message">
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="d-flex justify-content-center">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('message')}}!</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @include('frontend.includes.error_message')
            <!-- Error Message -->

            <div class="row">
                <form action="{{route('payment.process')}}" method="POST"
                    novalidate
                    role="form"
                    class="require-validation"
                    data-cc-on-file="false"
                    data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                    id="payment-form">
                    @csrf
                    <input type="hidden" name="shipping_type" id="shippingType">
                    <input type="hidden" name="totalAmount" class="cart_total" id="totalAmount" value="{{$cart_total}}">
                    <input type="hidden" name="tax_id" id="taxId">
                    <input type="hidden" name="shipping_cost_temp" id="shippingCost">
                    <input type="hidden" name="coupon_code_temp" id="couponCode">
                    <input type="hidden" name="coupon_value_temp" id="couponValue">

                    <!-- Paystack -->
                    <input type="hidden" name="email" id="emailPaystack" @auth value="{{auth()->user()->email}}" @endauth>
                    <input type="hidden" name="orderID" value="345">
                    <input type="hidden" name="amount" id="totalAmountPaystack" class="cart_total" value="{{$cart_total}}">
                    <input type="hidden" name="quantity" value="{{Cart::count()}}">
                    <input type="hidden" name="currency" value="NGN">
                    <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
                    <!--/ Paystack -->


                    <div class="row">
                        <div class="col-md-6 mar-top-30">

                            <!-- billing Details -->
                            @include('frontend.pages.checkout_page.billing_details')

                            <!-- Create Account -->
                            @include('frontend.pages.checkout_page.create_account')


                            <!-- Shipping Details -->
                            @include('frontend.pages.checkout_page.shipping_details')
                        </div>

                        <div class="col-md-6 mar-top-30">
                            <h3 class="section-title">@lang('file.Your order')</h3>
                            <div class="cart-subtotal">

                                <!--Product List-->
                                @include('frontend.pages.checkout_page.product_list')


                                <div class="subtotal mt-3">
                                    <div class="label">@lang('file.Subtotal')</div>
                                    <input type="hidden" name="subtotal" class="cartSubtotal" value="{{$cart_subtotal}}">
                                    <div class="price">
                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                            <span class="cartSubtotal">{{ number_format((float)$cart_subtotal * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}</span> @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                        @else
                                            @include('frontend.includes.SHOW_CURRENCY_SYMBOL') <span class="cartSubtotal">{{number_format((float)$cart_subtotal * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '')}}</span>
                                        @endif
                                    </div>
                                </div>

                                <!--Coupon-->
                                @include('frontend.pages.checkout_page.coupon')

                                <!--Shipping-->
                                @include('frontend.pages.checkout_page.shipping_option')

                                <div class="d-flex justify-content-between">
                                    <div class="label">{{__('file.Tax')}}</div>
                                    <div class="price">
                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                            <span class="tax_rate">0</span> @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                        @else
                                            @include('frontend.includes.SHOW_CURRENCY_SYMBOL') <span class="tax_rate">0</span>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                <div class="total">
                                    <div class="label">{{__('file.Total')}}</div>
                                    <div class="price">
                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                            <span class="total_amount">{{number_format((float)$cart_total * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '')}}</span> @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                        @else
                                            @include('frontend.includes.SHOW_CURRENCY_SYMBOL') <span class="total_amount">{{number_format((float)$cart_total * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <!--/Shipping-->

                                <!-- Payment Option -->
                                @include('frontend.pages.checkout_page.payment_options')
                                <!--/ Payment Option -->

                                <!-- Terms & Condition -->
                                <div class="custom-control custom-checkbox text-center mt-5 mb-5">
                                    <input type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="acceptTerms">@lang('file.I have read and accecpt the') <a class="theme-color" @isset($terms_and_condition_page_slug) href="{{route('page.Show',$terms_and_condition_page_slug)}}" target="__blank" @endisset >Terms & Conditions</a></label>
                                </div>
                                <!--/ Terms & Condition -->
                            </div>

                            <div class="mar-top-30">
                                <button type="submit" id="paymentProccedBtn" disabled class="btn btn-success text-center w-100">{{__('file.Payment Procced')}}</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </section>
</div>

@endsection

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_CLIENT_ID')}}&currency=USD" data-namespace="paypal_sdk"></script>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>


    <script type="text/javascript">
        $(function(){

            function amountSetting(data){
                $('.tax_rate').text(data.tax_rate);
                $('#taxId').val(data.tax_id); //For Form
                $('.total_amount').text(data.total_amount);
                $('#totalAmount').val(data.total_amount); //For Form
                $('#totalAmountPaystack').val(data.total_amount); //For Paystack
            }

            var billingCountry = $("#billingCountry").val();
            if (billingCountry) {
                var couponCode   = $('#couponCode').val();
                var shippingCost = $("#shippingCost").val();
                $.ajax({
                    url: "{{ route('cart.country_wise_tax') }}",
                    type: "GET",
                    data: {
                        billing_country:billingCountry,
                        coupon_code:couponCode,
                        shipping_cost:shippingCost,
                    },
                    success: function (data) {
                        amountSetting(data);
                    }
                });
            }

            $('#billingCountry').change(function() {
                var billingCountry = $("#billingCountry").val();
                var couponCode = $('#couponCode').val();
                var shippingCost = $("#shippingCost").val();
                $.ajax({
                    url: "{{ route('cart.country_wise_tax') }}",
                    type: "GET",
                    data: {
                        billing_country:billingCountry,
                        coupon_code:couponCode,
                        shipping_cost:shippingCost,
                    },
                    success: function (data) {
                        console.log(data);
                        amountSetting(data);
                    }
                })
            });


            //Coupon
            $('#applyCoupon').on("click",function(e){
                e.preventDefault();
                var taxId = $('#taxId').val();
                var shippingCost = $('.shippingCharge:checked').val();
                var coupon_code = $('#coupon_code').val();
                $.ajax({
                    url: "{{ route('cart.apply_coupon') }}",
                    type: "GET",
                    data: {tax_id:taxId, coupon_code:coupon_code,shipping_cost:shippingCost},
                    success: function (data) {
                        console.log(data)
                        if (data.type=='success') {
                            amountSetting(data);

                            couponValue = +data.coupon_value;

                            $('#couponValue').val(data.couponValue); //For Form
                            $('#couponValueDisplay').text(couponValue.toFixed(2));

                            if(data.coupon_value==0 && data.expired===true){
                                $('#invalidCoupon').text('Coupon is Expired !!');
                                $('#couponValue').val(couponValue); //For Form
                                $('#couponValueDisplay').text(couponValue.toFixed(2));
                            }
                            else if(data.coupon_value==0){
                                $('#invalidCoupon').text('Invalid Coupon !!');
                                $('#couponValue').val(couponValue); //For Form
                                $('#couponValueDisplay').text(couponValue.toFixed(2));
                            }
                            else{
                                $('#invalidCoupon').empty();
                                $('#couponValue').val(couponValue); //For Form
                                $('#couponValueDisplay').text(couponValue.toFixed(2));
                            }
                        }
                    }
                })
            });

            //Shipping
            $('.shippingCharge').on("click",function(e){
                var shippingCost = $(this).val();
                $('#shippingCost').val(shippingCost);

                var shipping_type = $(this).data('shipping_type');
                $('#shippingType').val(shipping_type);

                var couponValue = $('#couponValue').val();
                var taxId = $('#taxId').val();

                $.ajax({
                    url: "{{ route('cart.shipping_charge') }}",
                    type: "GET",
                    data: {shipping_cost:shippingCost,coupon_value:couponValue,tax_id:taxId},
                    success: function (data) {
                        console.log(data)
                        if (data.type=='success') {
                            $('#couponValue').val(data.coupon_value); //For Form
                            amountSetting(data);
                        }
                    }
                })
            });


            let paymentType;
            $("input[type='checkbox']").change(function() {
                if($(this).is(":checked")) {
                    if($('input[name="payment_type"]').is(":checked")) {
                        $("#paymentProccedBtn").removeAttr("disabled");
                        console.log("Radio button is checked");
                        console.log("And Checkbox is checked");
                    } else {
                        $("#paymentProccedBtn").attr("disabled", "disabled");
                        console.log("Radio button is unchecked");
                        console.log("But Checkbox is checked");
                    }
                }else {
                    $("#paymentProccedBtn").attr("disabled", "disabled");
                    console.log("Checkbox is unchecked");
                }
            });


            //-- For Paystack ------
            $('input[name="billing_email"]').keyup(function(){
                var billing_email = $('input[name="billing_email"]').val();
                 $('#emailPaystack').val(billing_email);
            });

            const paypalPaymentGateway = () =>{
                $("#payment-form").unbind();
                $('#stripeSection').addClass('d-none');
                $('.pay_now_div').hide();
                var totalAmountP = parseFloat($("input[name=totalAmount]").val()).toFixed(2);
                let x = 0;
                paypal_sdk.Buttons({
                    createOrder: function(data, actions) {
                        $.ajax({
                            url: "{{route('payment.process')}}",
                            method: "POST",
                            data: $('#payment-form').serialize(),
                            success: function (data) {
                                x = 1;
                                console.log(data);
                            }
                        });
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: totalAmountP,
                                    currency_code: "USD",
                                }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                        });
                    }
                }).render('#paypal-button-container');
            }

            const stripePaymentGateway = () =>{
                $('#stripeSection').removeClass('d-none');
                $('#orderBtn').prop("disabled",false).prop("title",'Pay Now');

                var $form = $(".require-validation");
                $('form').bind('submit', function(e) {
                    if (!$form.data('cc-on-file')) {
                        e.preventDefault();
                        Stripe.setPublishableKey($form.data('stripe-publishable-key'));
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
                        // insert the token into the form so it gets submitted to the server
                        $form.find('input[type=text]').empty();
                        $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                        $form.get(0).submit();
                    }
                }
            }

        });

    </script>
@endpush
