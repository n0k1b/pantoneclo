<!-- Same for all features -->
@if (isset($all) && $all)
    <script type="text/javascript" src="{{asset('public/js/admin/includes/submit_button_click.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/store.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/update_button_click.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/update.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/active.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/inactive.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/delete.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/bulk_action.js')}}"></script>
@endif



<!-- If Specific -->
@if (isset($store) && $store)
    <script type="text/javascript" src="{{asset('public/js/admin/includes/submit_button_click.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/store.js')}}"></script>
@endif


@if (isset($update) && $update)
    <script type="text/javascript" src="{{asset('public/js/admin/includes/update_button_click.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/update.js')}}"></script>
@endif

@if (isset($delete) && $delete)
    <script type="text/javascript" src="{{asset('public/js/admin/includes/delete.js')}}"></script>
@endif


@if (isset($action) && $action)
    <script type="text/javascript" src="{{asset('public/js/admin/includes/active.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/inactive.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/delete.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/js/admin/includes/bulk_action.js')}}"></script>
@endif
