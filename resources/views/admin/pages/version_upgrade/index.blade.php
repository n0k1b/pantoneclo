@extends('admin.main')
@section('title','Admin | New Release Version')
@section('admin_content')

    <div class="mt-3 mb-3" id="errorMessage"></div>


    <!-- Old Version -->
    <section id="oldVersionSection" class="d-none container mt-5 text-center">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center text-info">Your current version is <span>{{env('VERSION')}}</span></h4>
                <p>Please wait for upcoming version</p>
            </div>
        </div>
    </section>


    <!-- For New Version -->
    <section id="newVersionSection" class="d-none container mt-5 text-center">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center text-success">A new version <span id="newVersionNo"></span> has been released.</h4>
                <p>Before upgrading, we highly recomended you to keep a backup of your current script and database.</p>
            </div>
        </div>

        <div class="card mt-3">
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

        <button id="upgrade" type="button" class="mt-5 mb-5 btn btn-primary btn-lg">Upgrade</button>
    </section>

@endsection

@push('scripts')
    <script>
        let clientCurrrentVersion = {!! json_encode(env("VERSION"))  !!};
        let clientCurrrentBugNo   = {!! json_encode(env("BUG_NO"))  !!};
    </script>
    <script type="text/javascript" src="{{asset('public/js/admin/version_upgrade/index.js')}}"></script>


    <script type="text/javascript">

        (function ($) {
            "use strict";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#upgrade').on('click', function(e){
                e.preventDefault();
                $('#spinner').removeClass('d-none');
                $('#upgrade').text('Upgrading...');
                $.post({
                    url: "{{route('version-upgrade')}}",
                    type: "POST",
                    data: {data:fetchVersionUpgradeApiData, general: fetchGeneralApiData},
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
                        $('#upgrade').text('Upgrade');
                    },
                    success: function (data) {
                        console.log(data);
                        if (data == 'success') {
                            localStorage.setItem('version_upgrade_status','done');
                            $('#spinner').addClass('d-none');
                            $('#upgrade').text('Upgrade');
                            window.location.href = "{{ route('admin.dashboard')}}";
                        }

                    }
                })
            })
        })(jQuery);
        // Auto Load End
    </script>

@endpush
