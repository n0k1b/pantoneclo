
@extends('frontend.layouts.master')
@section('extra_css')
<style type="text/css">
    .payment{border:1px solid #f2f2f2;border-radius:3px;background:#f30808;padding-bottom:30px;}.payment_header{background:var(--theme-color);padding:15px;border-radius:3px 3px 0 0}.check{margin:0 auto;width:50px;height:50px;border-radius:100%;background:#fff;text-align:center}.check i{vertical-align:middle;line-height:50px;font-size:30px}.content{text-align:center}.content h1{font-size:25px;padding-top:25px}
</style>
@endsection
@section('frontend_content')

<section>
    <div class="container">
        <div class="row">
        <div class="col-md-6 mx-auto mt-5">
            <div class="payment mb-5">
                <div class="payment_header">
                    <div class="check"><i class="las la-times"></i></div>
                </div>
                <div class="content">
                    <h1 class="text-light">@lang('file.Order Cancel')</h1>

                    @if (session()->has('error'))
                        <p class="text-light">{{ session()->get('error')}}</p>
                    @endif
                    <p class="mb-3 text-light">@lang("file.Your order has been canceled").</p>
                    <p class="mb-3 text-light">Please add to cart again</p>
                    <a class="button style1 md" href="{{route('cartpro.home')}}">@lang('file.Go to Home')</a>
                </div>

            </div>
        </div>
        </div>
    </div>
</section>

@endsection
