@extends('admin.main')
@section('title','Admin | Developer Section')
@section('admin_content')


<div class="container-fluid mb-3">
    <div class="row">
        <div class="col-4">
            <div class="card mb-0">
                <div id="collapse1" class="collapse show" aria-labelledby="generalSettings" data-parent="#accordion">
                    <div class="card-body">
                        <div class="list-group" id="list-tab" role="tablist">
                            <a class="list-group-item list-group-item-action active" id="auto-update-setting" data-toggle="list" href="#autoUpdateSetting" role="tab" aria-controls="home">@lang('file.Auto Update Setting')</a>
                            {{-- <a class="list-group-item list-group-item-action" id="convert-demo" data-toggle="list" href="#convertDemo" role="tab" aria-controls="home">@lang('file.Convert Demo')</a>
                            <a class="list-group-item list-group-item-action" id="convert-client" data-toggle="list" href="#convertClient" role="tab" aria-controls="home">@lang('file.Convert Client')</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-8">

            @include('admin.includes.alert_message')


            <div class="tab-content" id="nav-tabContent">
                <!-- Auto Update -->
                <div class="tab-pane fade show active" id="autoUpdateSetting" role="tabpanel" aria-labelledby="auto-update-setting">
                    @include('admin.pages.developer_section.includes.auto-update')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
