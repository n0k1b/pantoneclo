
<!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">{{__('file.Add Page')}}</h5> &nbsp;&nbsp;&nbsp;&nbsp; <span id="error_message"></span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="submitForm"  class="form-horizontal">
          @csrf
            <div class="container-fluid"><span id="errorMessage"></span></div>


            <div class="modal-body">

                <div class="form-group">
                    <label class="text-bold">{{__('file.Page Name')}} &nbsp; <span class="text-danger">*</span></label>
                    <input type="text" name="page_name" id="page_name"  class="form-control" placeholder="{{__('file.Page Name')}}" >
                    <small class="form-text text-muted"> <span id="errorMessge"></span></small>
                </div>

                <div class="form-group">
                    <label for="inputEmail3"><b>{{__('file.Body')}} <span class="text-danger">*</span></b></label>
                    <textarea name="body" id="body" class="form-control text-editor"></textarea>
                </div>

                <div class="form-group">
                    <label class="text-bold">{{__('file.Meta Title')}} &nbsp;</label>
                    <input type="text" name="meta_title" id="meta_title"  class="form-control" placeholder="{{__('file.Meta Title')}}" >
                </div>
                <div class="form-group">
                    <label class="text-bold">{{__('file.Meta Description')}} &nbsp;</label>
                    <textarea name="meta_description" id="text" class="form-control" cols="30" rows="5" ></textarea>
                    <span class="pull-right label label-default" id="count_message"></span>
                </div>
                <div class="form-group">
                    <label class="text-bold">{{__('file.Meta URL')}} &nbsp;</label>
                    <input type="text" name="meta_url" id="meta_url"  class="form-control" placeholder="{{__('file.Meta URL')}}" >
                </div>
                <div class="form-group">
                    <label class="text-bold">{{__('file.Meta Type')}} &nbsp;</label>
                    <input type="text" name="meta_type" id="meta_type"  class="form-control" placeholder="{{__('file.Meta Type')}}" >
                </div>

                <br>
                <div class="form-group form-check">
                    <input type="checkbox" checked class="form-check-input" name="is_active" id="is_active" value="1">
                    <label class="form-check-label text-bold" for="exampleCheck1">{{__('file.Active')}}</label>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button type="submit" name="action_button" id="submitButton" class="btn btn-primary">@lang('file.Submit')</button>
            </div>

        </form>
      </div>
    </div>
  </div>


  @push('scripts')
    <script type="text/javascript">
        (function ($) {
            "use strict";

                var text_max = 160;
                $('#count_message').html('0 / ' + text_max );

                $('#text').keyup(function() {
                    var text_length = $('#text').val().length;
                    var text_remaining = text_max - text_length;
                    $('#count_message').html(text_length + ' / ' + text_max);
                });

        })(jQuery);
    </script>
@endpush
