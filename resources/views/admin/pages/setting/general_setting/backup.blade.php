<div class="card">
    <h4 class="card-header p-3"><b>@lang('file.System Backup')</b></h4>
    <hr>
    <div class="card-body">

        <div class="card-title m-3">
            <small><i>[ N.B : @lang('file.After clicking, you have to wait few moments'). @lang('file.And if you download only files, please extract the vendor.zip file before using the backup') ] </i></small>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <form action="{{route('system.backup')}}" method="post">
                    @csrf
                    <input type="hidden" name="type" value="files">
                    <button type="submit" class="btn btn-primary mt-3">@lang('file.Only Files')</button>
                </form>
            </div>
            <div class="col-md-4">
                <form action="{{route('system.backup')}}" method="post">
                    @csrf
                    <input type="hidden" name="type" value="database">
                    <button type="submit" class="btn btn-primary mt-3">@lang('file.Only Database')</button>
                </form>
            </div>
            <div class="col-md-2"></div>
            {{-- <div class="col-md-4">
                <form action="{{route('system.backup')}}" method="post">
                    @csrf
                    <input type="hidden" name="type" value="both">
                    <button type="submit" class="btn btn-primary mt-3">@lang('file.Both Files and Database')</button>
                </form>
            </div> --}}
        </div>

    </div>
</div>
