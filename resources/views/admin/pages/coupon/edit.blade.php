@extends('admin.main')
@section('title','Admin | Coupon | Edit')

@section('admin_content')

<section>

    <div class="container-fluid mb-3">
        <h4 class="font-weight-bold mt-3"><a class="btn btn-sm btn-default mr-1" href="{{route('admin.coupon.index')}}"><i class="dripicons-arrow-thin-left"></i></a> @lang('file.Coupon Edit')</h4>
        <div id="alert_message" role="alert"></div>
        <br>
    </div>

    <div class="container">
        <form id="submitForm" method="POST">
            @csrf

            <input type="hidden" name="coupon_id" value="{{$coupon->id}}">
            <input type="hidden" name="coupon_translation_id" id="coupon_translation_id" @if($coupon->couponTranslations->isNotEmpty()) value="{{$coupon->couponTranslations[0]->id}}" @endif >

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-3 col-form-label"><b>{{ trans('file.Coupon Name') }}<span class="text-danger">*</span></b></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="coupon_name" id="coupon_name" class="form-control" @if($coupon->couponTranslation || $coupon->couponTranslationEnglish) value="{{$coupon->couponTranslation->coupon_name ?? $coupon->couponTranslationEnglish->coupon_name ?? null }}" @endif placeholder="Type Coupon Name" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-3 col-form-label"><b>{{ trans('file.Code') }} <span class="text-danger">*</span></b></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="coupon_code" id="coupon_code" class="form-control" value="{{$coupon->coupon_code}}" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-3 col-form-label"><b>{{ trans('file.Discount Type') }} <span class="text-danger">*</span> </b></label>
                                        <div class="col-sm-9">
                                            <select name="discount_type" class="form-control selectpicker" title='{{__('file.Select Coupon')}}'>
                                                <option value="fixed" @if($coupon->discount_type=='fixed') selected @endif>{{__('file.Fixed')}}</option>
                                                <option value="percent" @if($coupon->discount_type=='percent') selected @endif>{{__('file.Percent')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-3 col-form-label"><b>{{ trans('file.Value') }} <span class="text-danger">*</span></b></label>
                                        <div class="col-sm-9">
                                            <input type="number" min="0" name="value" class="form-control" @if(env('FORMAT_NUMBER')) value="{{number_format((float)$coupon->value, env('FORMAT_NUMBER'), '.', '')}}" @endif >
                                        </div>
                                    </div>

                                    <input type="checkbox" name="is_expire" value="1" {{$coupon->is_expire ? 'checked':''}} data-toggle="collapse" data-target="#isExpire" aria-expanded="false" aria-controls="isExpire"> <strong>@lang('file.Enable Expiry Date')</strong>
                                    <div class="collapse {{$coupon->is_expire ? 'show':''}}" id="isExpire">
                                        <div class="card card-body">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label"><b>{{ trans('file.Start Date') }}</b></label>
                                                <div class="col-sm-9">
                                                    <input type="date" min="0" name="start_date" class="form-control" placeholder="Start Date" value="{{$coupon->start_date}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label"><b>{{ trans('file.End Date') }}</b></label>
                                                <div class="col-sm-9">
                                                    <input type="date" min="0" name="end_date" class="form-control" placeholder="End Date" value="{{$coupon->end_date}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <input class="mt-3" type="checkbox" name="is_limit" {{$coupon->is_limit ? 'checked':''}} value="1 " data-toggle="collapse" data-target="#isLimit" aria-expanded="false" aria-controls="isLimit"> <strong>@lang('file.Enable Usage Limit Per Coupon')</strong>
                                    <div class="collapse {{$coupon->is_limit ? 'show':''}}" id="isLimit">
                                        <div class="card card-body">

                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label"><b>{{ trans('file.Usage Limit Per Coupon') }}</b></label>
                                                <div class="col-sm-9">
                                                    <input type="number" min="0" name="limit_qty" class="form-control" placeholder="Limit Quantity" value="{{$coupon->limit_qty}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label"><b>{{ trans('file.Coupon Remaining') }}</b></label>
                                                <div class="col-sm-9">
                                                    <input type="number" id="couponRemaining" readonly min="0" name="coupon_remaining" class="form-control" placeholder="Limit Quantity" value="{{$coupon->coupon_remaining}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label for="inputEmail3"><b>{{ trans('file.Status') }}</b></label>
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" name="is_active" @if($coupon->is_active==1) checked @endif value="1" id="isActive">
                                            <span>{{__('file.Enable the coupon')}}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <br><br>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-success btn-block">{{__('file.Update')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

</section>
@endsection

@push('scripts')
    <script type="text/javascript">
        (function ($) {
            "use strict";

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let date = $('.date');
                date.datepicker({
                    format: "yyyy/mm/dd",
                    autoclose: true,
                    todayHighlight: true,
                    //endDate: new Date()
                });
                //----------Update Data----------------------

                $('#submitForm').on('submit', function (e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{route('admin.coupon.update')}}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function (data) {
                            console.log(data);

                            let html = '';
                            if (data.errors) {
                                html = '<div class="alert alert-danger">';
                                for (let count = 0; count < data.errors.length; count++) {
                                    html += '<p>' + data.errors[count] + '</p>';
                                }
                                html += '</div>';
                                $('#alert_message').html(html);
                                setTimeout(function() {
                                    $('#alert_message').fadeOut("slow");
                                }, 3000);
                            }
                            else if (data.error) {
                                html = '<div class="alert alert-danger">' + data.error + '</div>';
                                $('#alert_message').html(html);
                                setTimeout(function() {
                                    $('#alert_message').fadeOut("slow");
                                }, 3000);
                            }
                            else if(data.success){
                                $('#couponRemaining').val(data.coupon_remaining);
                                $('#alert_message').fadeIn("slow"); //Check in top in this blade
                                $('#alert_message').addClass('alert alert-success').html(data.success);
                                setTimeout(function() {
                                    $('#alert_message').fadeOut("slow");
                                }, 3000);
                            }

                        }
                    });
                });

            })(jQuery);
    </script>
@endpush
