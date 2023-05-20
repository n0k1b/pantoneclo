
<!-- Modal -->
<div class="modal fade" id="editFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">{{__('file.Edit Page')}} | <a class="btn btn-default btn-sm" id="page_url" href="" target="_blank"><i class="dripicons-preview"></i>@lang('file.View Page')</a></h5> &nbsp;&nbsp;&nbsp;&nbsp; <span id="error_message_edit"></span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="updateForm" class="form-horizontal">
          @csrf
          <input type="hidden" name="page_id" id="page_id">
          <input type="hidden" name="page_translation_id" id="page_translation_id">

          <div id="errorMessageEdit" role="alert"></div>

            <div class="modal-body">

              <div class="form-group">
                <label class="text-bold">{{__('file.Page Name')}} &nbsp; <span class="text-danger">*</span></label>
                <input type="text" name="page_name" id="page_name_edit"  class="form-control" placeholder="{{__('file.Page Name')}}" >
              </div>

              <div class="form-group">
                <label for="inputEmail3"><b>{{__('file.Body')}} <span class="text-danger">*</span></b></label>
                <textarea name="body" id="body_edit" class="form-control text-editor"></textarea>
            </div>


            <div class="form-group">
                <label class="text-bold">{{__('file.Meta Title')}} &nbsp; <span class="text-danger">*</span></label>
                <input type="text" name="meta_title" id="meta_title_edit"  class="form-control" placeholder="{{__('file.Meta Title')}}" >
            </div>
            <div class="form-group">
                <label class="text-bold">{{__('file.Meta Description')}} &nbsp; <span class="text-danger">*</span></label>
                {{-- <input type="text" name="meta_description" id="meta_description_edit"  class="form-control" placeholder="{{__('file.Meta Description')}}" > --}}

                <textarea name="meta_description" id="meta_description_edit" class="form-control" cols="30" rows="5" ></textarea>
                <span class="pull-right label label-default" id="count_message_edit"></span>

            </div>
            <div class="form-group">
                <label class="text-bold">{{__('file.Meta URL')}} &nbsp; <span class="text-danger">*</span></label>
                <input type="text" name="meta_url" id="meta_url_edit"  class="form-control" placeholder="{{__('file.Meta URL')}}" >
            </div>
            <div class="form-group">
                <label class="text-bold">{{__('file.Meta Type')}} &nbsp; <span class="text-danger">*</span></label>
                <input type="text" name="meta_type" id="meta_type_edit"  class="form-control" placeholder="{{__('file.Meta Type')}}" >
            </div>

            <br>
              <div class="form-group form-check">
                <input type="checkbox" checked class="form-check-input" name="is_active" id="is_active_edit" value="1">
                <label class="form-check-label text-bold" for="exampleCheck1">{{__('file.Active')}}</label>
              </div>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button type="submit" name="action_button" id="updateButton" class="btn btn-primary">@lang('file.Submit')</button>
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
                $('#count_message_edit').html('0 / ' + text_max );

                $('#meta_description_edit').keyup(function() {
                    var text_length = $('#meta_description_edit').val().length;
                    var text_remaining = text_max - text_length;
                    $('#count_message_edit').html(text_length + ' / ' + text_max);
                });

        })(jQuery);
    </script>
@endpush
