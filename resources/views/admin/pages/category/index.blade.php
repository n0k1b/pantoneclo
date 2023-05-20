@extends('admin.main')
@section('title','Admin | Category')
@section('admin_content')
<section>

        <div class="container-fluid"><span id="alert_message"></span></div>

        <div class="container-fluid mb-3">
            @if (auth()->user()->can('category-store'))
                <button type="button" class="btn btn-info parent_load" name="create_record" id="create_record">
                    <i class="fa fa-plus"></i> @lang('file.Add Category')
                </button>
            @endif
            @if (auth()->user()->can('category-action'))
                <button type="button" class="btn btn-danger" name="bulk_delete" id="bulk_action">
                    <i class="fa fa-minus-circle"></i> @lang('file.Bulk Action')
                </button>
            @endif
        </div>

        <div class="table-responsive">
            <table id="dataListTable" class="table ">
                <thead>
                    <tr>
                        <th class="not-exported"></th>
                        <th scope="col">{{__('file.Image')}}</th>
                        <th scope="col">{{__('file.Category Name')}}</th>
                        <th scope="col">@lang('file.Parent')</th>
                        <th scope="col">@lang('file.Status')</th>
                        <th scope="col">@lang('file.Action')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>

    @include('admin.pages.category.create')
    @include('admin.pages.category.edit_modal')
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

            $('#create_record').click(function () {
                $('#formModal').modal('show');
            });
            let indexURL      = "{{route('admin.category.datatable') }}";
            let storeURL      = "{{route('admin.category.store')}}";
            let editURL       = "{{route('admin.category.edit')}}";
            let updateURL     = "{{route('admin.category.update')}}";
            let activeURL     = "{{route('admin.category.active')}}";
            let inactiveURL   = "{{route('admin.category.inactive')}}";
            let deleteURL     = "{{route('admin.category.delete')}}";
            let bulkActionURL = "{{route('admin.category.bulk_action')}}";
            @include('admin.includes.common_js.common_word')
        </script>
        <!-- index and edit -->
        <script type="text/javascript" src="{{asset('public/js/admin/pages/category/index.js')}}"></script>

        <!-- Common Action For All CRUD-->
        @include('admin.includes.common_action',['all'=>true])

    @endpush
