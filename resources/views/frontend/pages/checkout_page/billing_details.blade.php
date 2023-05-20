<h3 class="section-title">@lang('file.Billing Details')</h3>
<div class="row">
    <div class="col-sm-6">
        <input class="form-control" type="text" name="billing_first_name" @auth value="{{auth()->user()->first_name}}" @endauth placeholder="@lang('file.First Name') *">
    </div>
    <div class="col-sm-6">
        <input class="form-control" type="text" name="billing_last_name" @auth value="{{auth()->user()->last_name}}" @endauth  placeholder="@lang('file.Last Name') *">
    </div>


    <div class="col-sm-6">
        <input class="form-control" type="email" name="billing_email" @auth value="{{auth()->user()->email}}" @endauth placeholder="@lang('file.Email') *">
    </div>
    <div class="col-sm-6">
        <input class="form-control" type="number" name="billing_phone" @auth value="{{auth()->user()->phone}}" @endauth min='0' onkeypress="return isNumberKey(event)" placeholder="@lang('file.Phone') *">
    </div>

    <div class="col-12">
        <div class="form-group">
            <select class="form-control" name="billing_country" id="billingCountry">
                <option value="">* @lang('file.Select Country')</option>
                @foreach ($countries as $country)
                    <option selected value="{{$country->country_name}}" @isset($billing_address) {{$country->country_name==$billing_address->country ? 'selected':''}}  @endisset>{{$country->country_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12">
        <input value="test Addess"
        class="form-control" type="text" name="billing_address_1" @isset($billing_address) value="{{$billing_address->address_1 ?? ''}}" @endisset placeholder="@lang('file.Street Address')">
    </div>
    <div class="col-12">
        <input value="test Addess 2"
        class="form-control" type="text" name="billing_address_2" @isset($billing_address) value="{{$billing_address->address_2 ?? ''}}" @endisset placeholder="@lang('file.Apartment, suite, unit etc. (optional)')">
    </div>
    <div class="col-12">
        <input value="test city"
        class="form-control" type="text" name="billing_city" @isset($billing_address) value="{{$billing_address->city ?? ''}}" @endisset placeholder="@lang('file.City / Town')">
    </div>
    <div class="col-sm-6">
        <input value="test state"
        class="form-control" type="text" name="billing_state" @isset($billing_address) value="{{$billing_address->state ?? ''}}" @endisset placeholder="@lang('file.State / County')">
    </div>
    <div class="col-sm-6">
        <input value="123"
        class="form-control" type="text" name="billing_zip_code" @isset($billing_address) value="{{$billing_address->zip_code ?? ''}}" @endisset placeholder="@lang('file.Postcode / Zip')">
    </div>
</div>
