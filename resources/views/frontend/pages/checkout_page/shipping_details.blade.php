<div class="custom-control custom-checkbox mt-4 mb-3">
    <input type="checkbox" data-bs-toggle="collapse" href="#shipping_address_collapse" role="button" aria-expanded="false" aria-controls="shipping_address_collapse" class="custom-control-input" id="shipping_address_check" name="shipping_address_check" value="1">
    <label class="label" for="shipping_address_check">@lang('file.Ship to a different address')</label>
</div>

<div class="collapse" id="shipping_address_collapse">
    <div class="row">
        <div class="col-sm-6">
            <input class="form-control" type="text" name="shipping_first_name" placeholder="@lang('file.First Name') *">
        </div>
        <div class="col-sm-6">
            <input class="form-control" type="text" name="shipping_last_name" placeholder="@lang('file.Last Name') *">
        </div>
        <div class="col-sm-6">
            <input class="form-control" type="text" name="shipping_email" placeholder="@lang('file.Email')">
        </div>
        <div class="col-sm-6">
            <input class="form-control" type="text" name="shipping_phone" min='0' onkeypress="return isNumberKey(event)" placeholder="@lang('file.Phone')">
        </div>

        <div class="col-12">
            <div class="form-group">
                <select class="form-control" name="shipping_country" id="shipping_country">
                    <option value="">@lang('file.Select Country')</option>
                    @foreach ($countries as $country)
                        <option value="{{$country->country_name}}" @isset($shipping_address) {{$country->country_name==$shipping_address->country ? 'selected':''}}  @endisset>{{$country->country_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <input class="form-control" type="text" name="shipping_address_1" @isset($shipping_address) value="{{$shipping_address->address_1 ?? ''}}" @endisset placeholder="@lang('file.Street Address')">
        </div>
        <div class="col-12">
            <input class="form-control" type="text" name="shipping_address_2" @isset($shipping_address) value="{{$shipping_address->address_2 ?? ''}}" @endisset placeholder="@lang('file.Apartment, suite, unit etc. (optional)')">
        </div>
        <div class="col-12">
            <input class="form-control" type="text" name="shipping_city" @isset($shipping_address) value="{{$shipping_address->city ?? ''}}" @endisset placeholder="@lang('file.City / Town')">
        </div>
        <div class="col-sm-6">
            <input class="form-control" type="text" name="shipping_state" @isset($shipping_address) value="{{$shipping_address->state ?? ''}}" @endisset placeholder="@lang('file.State / County')">
        </div>
        <div class="col-sm-6">
            <input class="form-control" type="text" name="shipping_zip_code" @isset($shipping_address) value="{{$shipping_address->zip_code ?? ''}}" @endisset placeholder="@lang('file.Postcode / Zip')">
        </div>
    </div>
</div>
