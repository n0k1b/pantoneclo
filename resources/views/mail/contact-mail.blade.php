@component('mail::message')

<span>
    Dear {{ config('app.name') }},
</span>
    <br>

<span>{{$data['message']}}</span>

<span>
    Regards,
    <br>
    {{$data['name']}}
    <br>
    {{$data['email']}}
</span>

@endcomponent
