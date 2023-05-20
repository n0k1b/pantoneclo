<div class="shipping">
    <div class="label">@lang('file.Shiping')</div>

    @if (isset($setting_free_shipping) && $setting_free_shipping->shipping_status==1)
        <div class="custom-control custom-radio mt-3">
            <input type="radio" id="customRadio1" data-shipping_type='free' name="shipping_cost" class="custom-control-input shippingCharge" value="{{$setting_free_shipping->minimum_amount ?? 0}}">
            <label class="custom-control-label" for="customRadio1">{{$setting_free_shipping->label ?? null}}
                <span class="price">
                    @if(env('CURRENCY_FORMAT')=='suffix')
                        {{ number_format((float)$setting_free_shipping->minimum_amount * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                    @else
                        @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$setting_free_shipping->minimum_amount  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                    @endif
                </span>
            </label>
        </div>
    @endif

    @if (isset($setting_local_pickup) && $setting_local_pickup->pickup_status==1)
        <div class="custom-control custom-radio mt-3">
            <input type="radio" id="customRadio2" data-shipping_type='local_pickup' name="shipping_cost" class="custom-control-input shippingCharge" value="{{$setting_local_pickup->cost ?? null}}">
            <label class="custom-control-label" for="customRadio2">{{$setting_local_pickup->label ?? null}}
                <span class="price">
                    @if(env('CURRENCY_FORMAT')=='suffix')
                        {{ number_format((float)$setting_local_pickup->cost * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                    @else
                        @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$setting_local_pickup->cost  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                    @endif
                </span>
            </label>
        </div>
    @endif

    @if (isset($setting_flat_rate) && $setting_flat_rate->flat_status==1)
        <div class="custom-control custom-radio mt-3">
            <input type="radio" id="customRadio3" data-shipping_type='flat_rate' name="shipping_cost" class="custom-control-input shippingCharge" value="{{$setting_flat_rate->cost ?? null}}">
            <label class="custom-control-label" for="customRadio3">{{$setting_flat_rate->label ?? null}}
                <span class="price">
                    @if(env('CURRENCY_FORMAT')=='suffix')
                        {{ number_format((float)$setting_flat_rate->cost * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                    @else
                        @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$setting_flat_rate->cost * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                    @endif
                </span>
            </label>
        </div>
    @endif
</div>
