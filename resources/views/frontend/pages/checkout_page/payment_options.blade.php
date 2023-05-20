<div class="payment-options">

    <div>
        @if (isset($cash_on_delivery) && $cash_on_delivery->status==1)
            <label class="custom-checkbox">
                <input type="radio" name="payment_type" id="cashOnDelivery" value="cash_on_delivery">
                <span class="sm-heading">@lang('file.Cash On Delivery')</span>
            </label>
        @endif

        @if (isset($paypal) && $paypal->status==1)
            <label class="custom-checkbox">
                <input type="radio" name="payment_type" id='paypal' value="paypal">
                <span class="card-options"><img class="lazy" data-src="{{asset('public/frontend/images/payment_gateway_logo/paypal.jpg')}}" alt="..."></span>
                <span class="sm-heading">{{__('file.Paypal')}}</span>
            </label>
        @endif

        @if (isset($stripe) && $stripe->status==1)
            <label class="custom-checkbox">
                <input type="radio" name="payment_type" id='stripe' value="stripe">
                <span class="card-options"><img class="lazy" data-src="{{asset('public/frontend/images/payment_gateway_logo/stripe.png')}}" alt="..."></span>
                <span class="sm-heading">{{__('file.Stripe')}}</span>
            </label>
        @endif

        @if (env('SSL_COMMERZ_STATUS')==1)
            <label class="custom-checkbox">
                <input type="radio" name="payment_type" id='sslcommerz' value="sslcommerz">
                <span class="card-options"><img class="lazy" data-src="{{asset('public/frontend/images/payment_gateway_logo/ssl_commerz.png')}}" alt="..."></span>
                <span class="sm-heading">{{__('file.SSL Commerz')}}</span>
            </label>
        @endif
    </div>
    <div class="mt-2">
        @if (env('RAZORPAY_STATUS')==1)
            <label class="custom-checkbox">
                <input type="radio" name="payment_type" id='razorpay' value="razorpay">
                <span class="card-options"><img class="lazy" data-src="{{asset('public/frontend/images/payment_gateway_logo/razorpay.png')}}"></span>
                <span class="sm-heading">{{__('file.Razorpay')}}</span>
            </label>
        @endif
        @if (env('PAYSTACK_STATUS')==1)
            <label class="custom-checkbox">
                <input type="radio" name="payment_type" id='paystack' value="paystack">
                <span class="card-options"><img class="lazy" data-src="{{asset('public/frontend/images/payment_gateway_logo/paystack.png')}}"></span>
                <span class="sm-heading">{{__('file.Paystack')}}</span>
            </label>
        @endif
    </div>
</div>
