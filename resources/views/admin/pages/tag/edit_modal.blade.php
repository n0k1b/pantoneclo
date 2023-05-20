
<!-- Modal -->
<div class="modal fade" id="editFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">{{__('file.Edit Tag')}}</h5> &nbsp;&nbsp;&nbsp;&nbsp; <span id="errorMessageEdit"></span>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="updateForm"  class="form-horizontal">
          @csrf
            <input type="hidden" name="tag_id" id="tagIdEdit">
            <input type="hidden" name="tag_translation_id" id="tagTranslationId">

            <div class="modal-body">

              <div class="form-group">
                <label>{{__('file.Tag Name')}} &nbsp; <span class="text-bold text-danger">*</span></label>
                <input type="text" name="tag_name" id="tagNameEdit"  class="form-control" placeholder="{{__('file.Tag Name')}}">
                <small class="form-text text-muted"> <span id="errorMessge"></span></small>
              </div>
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="is_active" id="isActiveEdit" value="1" id="isActive">
                <label class="form-check-label" for="exampleCheck1">{{__('file.Active')}}</label>
              </div>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button type="submit" name="action_button" id="updateButton" class="btn btn-primary">@lang('file.Update')</button>
            </div>

        </form>
      </div>
    </div>
  </div>
