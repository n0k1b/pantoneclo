@extends('admin.main')
@section('title','Admin | Notification')
@section('admin_content')

<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">

                    @foreach($all_notification as $notification)
                        <div class="appointment-list-item">
                            <h4><a class="mt-2" href="{{json_decode($notification->data)->link}}">{{json_decode($notification->data)->data}}</a><small class="ml-3" style="font-size: 12px">[Time: {{$notification->created_at}}]</small></h4>
                        </div>
                    @endforeach

                @if(count($all_notification) > 0)
                    <div class="text-center">
                        <a class="btn btn-danger" href="{{route('clearAll')}}" class="btn btn-link">{{__('Clear All')}}</a>
                    </div>
                @else
                    <p class="large-text dark-text text-center">{{__('No notifications for you at the moment!')}}</p>
                @endif
            </div>
        </div>
    </div>
</section>


@endsection
