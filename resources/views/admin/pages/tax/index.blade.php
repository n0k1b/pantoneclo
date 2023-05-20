@extends('admin.main')
@section('title','Admin | Tax')
@section('admin_content')

<section>
    <div class="container-fluid"><span id="general_result"></span></div>
    <div class="container-fluid mb-3">

        <h4 class="font-weight-bold mt-3">{{__('file.Taxes')}}</h4>
        <div id="alert_message" role="alert"></div>
        <br>

        @if (auth()->user()->can('tax-store'))
            <button type="button" class="btn btn-info" name="formModal" data-toggle="modal" data-target="#formModal">
                <i class="fa fa-plus"></i> {{__('file.Add Tax')}}
            </button>
        @endif

        @if (auth()->user()->can('tax-action'))
            <button type="button" class="btn btn-danger" id="bulk_action">
                <i class="fa fa-minus-circle"></i> {{trans('file.Bulk Action')}}
            </button>
        @endif

    </div>
    <div class="table-responsive">
    	<table id="dataListTable" class="table ">
    	    <thead>
        	   <tr>
        		    <th class="not-exported"></th>
        		    <th scope="col">{{trans('file.Tax Name')}}</th>
        		    <th scope="col">{{trans('file.Country')}}</th>
        		    <th scope="col">{{trans('file.Status')}}</th>
        		    <th scope="col">{{trans('file.action')}}</th>
        	   </tr>
    	  	</thead>
    	</table>
    </div>
</section>

@include('admin.pages.tax.create_modal')
@include('admin.pages.tax.edit_modal')
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
    let dataTableURL  = "{{route('admin.tax.datatable')}}";
    let storeURL      = "{{route('admin.tax.store')}}";
    let editURL       = "{{route('admin.tax.edit')}}";
    let updateURL     = "{{route('admin.tax.update')}}";
    let activeURL     = "{{route('admin.tax.active')}}";
    let inactiveURL   = "{{route('admin.tax.inactive')}}";
    let deleteURL     = "{{route('admin.tax.delete')}}";
    let bulkActionURL = "{{route('admin.tax.bulk_action')}}";

    @include('admin.includes.common_js.common_word')
</script>

<script type="text/javascript" src="{{asset('public/js/admin/pages/tax/datatable.js')}}"></script>
<script type="text/javascript" src="{{asset('public/js/admin/pages/tax/edit.js')}}"></script>

    <!-- Common Action For All CRUD-->
    @include('admin.includes.common_action',['all'=>true])

@endpush
