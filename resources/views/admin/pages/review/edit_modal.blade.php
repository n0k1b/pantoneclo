
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">{{__('file.Edit Review')}}</h5> &nbsp;&nbsp;&nbsp;&nbsp; <span id="error_message_edit"></span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="updateForm"  class="form-horizontal">
          @csrf
          <input type="hidden" name="review_id" id="reviewId">

            <div class="modal-body">

              <div class="form-group">
                <label class="text-bold">{{__('file.Rating')}} &nbsp; <span class="text-danger">*</span></label>
                <select name="rating" id="rating" class="from-control selectpicker">
                    <option value="1">@lang('file.1')</option>
                    <option value="2">@lang('file.2')</option>
                    <option value="3">@lang('file.3')</option>
                    <option value="4">@lang('file.4')</option>
                    <option value="5">@lang('file.5')</option>
                </select>
              </div>

              <div class="form-group">
                <label for="inputEmail3"><b>{{__('file.Comment')}} <span class="text-danger">*</span></b></label>
                <textarea name="comment" id="comment" class="form-control text-editor"></textarea>
            </div>

            <br>
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="status" id="status" value="approved">
                <label class="form-check-label text-bold" for="exampleCheck1">{{__('file.Approved')}}</label>
              </div>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button type="submit" name="action_button" id="submitButton" class="btn btn-primary">@lang('file.Submit')</button>
            </div>

        </form>
      </div>
    </div>
  </div>
