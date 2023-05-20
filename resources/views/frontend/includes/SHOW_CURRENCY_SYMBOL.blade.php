@php
    if (Session::has('currency_symbol')){
        $CHANGE_CURRENCY_SYMBOL = Session::get('currency_symbol');
    }else{
        $CHANGE_CURRENCY_SYMBOL = env('DEFAULT_CURRENCY_SYMBOL');
        Session::put('currency_symbol',$CHANGE_CURRENCY_SYMBOL);
    }
@endphp

{{-- {{$CHANGE_CURRENCY_SYMBOL!=NULL ? $CHANGE_CURRENCY_SYMBOL : env('DEFAULT_CURRENCY_SYMBOL')}} --}}
{{$CHANGE_CURRENCY_SYMBOL}}
