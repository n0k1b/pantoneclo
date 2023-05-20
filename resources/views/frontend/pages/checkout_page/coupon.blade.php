<div class="d-flex justify-content-between custom-control custom-checkbox">
    <div>
        <input type="checkbox" class="custom-control-input" id="apply_coupon" name="coupon_checked" value="1" data-bs-toggle="collapse" href="#apply_coupon_collapse" role="button" aria-expanded="false" aria-controls="apply_coupon_collapse">
        <label class="label">@lang('I have a coupon')</label>
    </div>
    <div>
        <div class="price">
            @if(env('CURRENCY_FORMAT')=='suffix')
                <span id="couponValueDisplay">0</span> @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
            @else
                @include('frontend.includes.SHOW_CURRENCY_SYMBOL') <span id="couponValueDisplay">0</span>
            @endif
        </div>
    </div>
</div>
<div class="collapse" id="apply_coupon_collapse">
    <div class="newsletter">
        <input type="text" placeholder="@lang('file.Enter Coupon Code')" name="coupon_code" id="coupon_code">
        <span class="text-danger" id="invalidCoupon"></span>
        <input type="hidden" name="coupon_value" id="coupon_value">
        <button class="button style1 btn-search" id="applyCoupon" type="submit">@lang('file.Apply')</button>
    </div>
</div>
