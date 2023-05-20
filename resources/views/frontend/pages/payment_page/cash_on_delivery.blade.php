@extends('frontend.layouts.master')
@section('frontend_content')

<div class="container">

    <!--Breadcrumb Area start-->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul>
                        <li><a href="{{route('cartpro.home')}}">@lang('file.Home')</a></li>
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
                <div class="col-md-1"></div>

                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('payment.pay.confirm', $payment_method)}}" method="post">
                                @csrf
                                <input type="hidden" name="requestData" value="{{$requestData}}">
                                <div class="d-grid gap-2 mx-auto">
                                    <button type="submit" class="btn btn-outline-success btn-lg">Pay Now</button>
                                </div>
                            </form>

                            {{-- <form action='https://www.2checkout.com/checkout/purchase' method='post'>
                                <input type='hidden' name='sid' value='123456789' />
                                <input type='hidden' name='mode' value='2CO' />
                                <input type='hidden' name='li_0_name' value='test' />
                                <input type='hidden' name='li_0_price' value='1.00' />
                                <input type='hidden' name='demo' value='Y' />
                                <input name='submit' type='submit' value='Checkout' />
                            </form> --}}
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('payment.pay.cancel', $payment_method)}}" method="post">
                                @csrf
                                <div class="d-grid gap-2 mx-auto">
                                    <button type="submit" onclick="confirm('Are you sure to cancel ?')" class="btn btn-outline-danger btn-lg">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-1"></div>
            </div>
        </div>
    </section>


</div>

@endsection


@push('scripts')
@endpush
