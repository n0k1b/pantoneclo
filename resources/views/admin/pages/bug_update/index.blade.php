@extends('admin.main')

@section('title','Admin | Bugs')

@section('admin_content')

    <div class="mt-3 mb-3" id="errorMessage"></div>


    <!-- Old Version -->
    <section id="noBug" class="d-none container mt-5 text-center">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center text-info">Your current version is <span>{{env('VERSION')}}</span></h4>
                <p>There is no bug</p>
            </div>
        </div>
    </section>

    <!-- For New Version -->
    <section id="bugSection" class="d-none container mt-5 text-center">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center text-success">Minor bug found. Please update it.</h4>
                <p>Before updating, we highly recomended you to keep a backup of your current script and database.</p>
            </div>
        </div>

        <div id="changeLog" class="d-none card mt-3">
            <div class="card-body">
                <h6 class="text-left p-4">New Change Log</h6>
                <ul class="list-group text-left" id="logUL">
                </ul>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3 mb-3">
            <div id="spinner" class="d-none spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <button id="update" type="button" class="mt-5 mb-5 btn btn-primary btn-lg">Update</button>
    </section>
@endsection


    @push('scripts')

    <script>
        let clientCurrrentVersion = {!! json_encode(env("VERSION"))  !!};
        let clientCurrrentBugNo   = {!! json_encode(env("BUG_NO"))  !!};
    </script>
    <script type="text/javascript" src="{{asset('public/js/admin/bug_update/index.js')}}"></script>

    <script type="text/javascript">
        (function ($) {
            "use strict";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#update').on('click', function(e){
                e.preventDefault();
                $('#spinner').removeClass('d-none');
                $('#update').text('Updating...');
                $.post({
                    url: "{{route('bug-update')}}",
                    data: {data:fetchBugApiData, general:fetchGeneralApiData},
                    error: function(response){
                        console.log(response.responseJSON.error[0]);
                        const html = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p> <strong>Error !!! </strong> ${response.responseJSON.error[0]} </p>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`;

                        $('#errorMessage').fadeIn("slow").html(html);
                        $('#spinner').addClass('d-none');
                        $('#update').text('update');
                    },
                    success: function (data) {
                        console.log(data);
                        if (data == 'success') {
                            localStorage.setItem('bug_status','done');
                            $('#spinner').addClass('d-none');
                            $('#update').text('update');
                            window.location.href = "{{ route('admin.dashboard')}}";
                        }
                    }
                })
            })
        })(jQuery);
    </script>
@endpush
