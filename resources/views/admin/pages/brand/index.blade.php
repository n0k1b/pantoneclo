@extends('admin.main')
@section('title','Admin | Brand')
@section('admin_content')
<section>

    <div class="container-fluid"><span id="alert_message"></span></div>

    <div class="container-fluid mb-3">

        <h4 class="font-weight-bold mt-3">@lang('file.Brand')</h4>
        <br>

        @if (auth()->user()->can('brand-store'))
            <button type="button" class="btn btn-info" name="create_record" id="create_record">
                <i class="fa fa-plus"></i> @lang('file.Add Brand')
            </button>
        @endif
        @if (auth()->user()->can('brand-action'))
            <button type="button" class="btn btn-danger" name="bulk_delete" id="bulk_action">
                <i class="fa fa-minus-circle"></i> {{trans('file.Bulk_Action')}}
            </button>
        @endif

    </div>
    <div class="table-responsive">
    	<table id="dataListTable" class="table ">
    	    <thead>
        	   <tr>
        		    <th class="not-exported"></th>
                    <th scope="col">@lang('file.Logo')</th>
        		    <th scope="col">@lang('file.Brand Name')</th>
        		    <th scope="col">@lang('file.Status')</th>
        		    <th scope="col">@lang('file.Action')</th>
        	   </tr>
    	  	</thead>
    	</table>
    </div>

</section>

@include('admin.pages.brand.create')
@include('admin.includes.confirm_modal')
@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        let indexURL      = "{{route('admin.brand') }}";
        let storeURL      = "{{route('admin.brand.store')}}";
        let activeURL     = "{{route('admin.brand.active')}}";
        let inactiveURL   = "{{route('admin.brand.inactive')}}";
        let deleteURL     = "{{route('admin.brand.delete')}}";
        let bulkActionURL = "{{route('admin.brand.bulk_action')}}";
        @include('admin.includes.common_js.common_word')

        (function ($) {
            "use strict";
            $('#create_record').click(function () {
                $('#action_button').val('{{trans("file.Add")}}');
                $('#action').val('{{trans("file.Add")}}');
                $('#formModal').modal('show');
            });

        })(jQuery);
    </script>

    <!-- index and edit -->
    <script type="text/javascript" src="{{asset('public/js/admin/pages/brand/datatable.js')}}"></script>

    <!-- Common Action For All CRUD-->
    @include('admin.includes.common_action',['store'=>true, 'action'=>true])
@endpush
