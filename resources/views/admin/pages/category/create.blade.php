   <!-- Modal -->
   <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__('file.Add Category')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
            <div class="modal-body">

                <div class="container-fluid"><span id="errorMessage"></span></div>


                <form method="post" id="submitForm" action="{{route('admin.category.store')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{__('file.Category Name')}} *</label>
                            <input type="text" name="category_name" id="category_name" required class="form-control" placeholder="{{__('file.Category Name')}}">
                        </div>


                        <div class="form-group col-md-6 ">
                            <label>{{__('file.Parent Category')}} </label>
                            <select class="form-control selectpicker" data-live-search="true" data-live-search-style="begins" title='{{__('file.Select Category')}}' name="parent_id">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 form-group mt-3">
                            <label for="exampleFormControlFile1">{{__('file.Insert Image')}}</label>
                            <input type="file" class="form-control-file" name="image" id="exampleFormControlFile1">
                        </div>

                        <div class="col-md-6 form-group mt-3">
                            <label for="">{{__('file.Icon')}}</label>
                            <input type="text" name="icon" id="cateogry_icon" class="form-control" placeholder="las la-table">
                        </div>

                        <div class="col-md-6 form-group">
                            <div class="form-check mt-3">
                                <input type="checkbox" class="form-check-input " value='1' name="top" checked>
                                <label class="form-check-label"  for="exampleCheck1">{{__('file.Show in Top categories Section')}}</label>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" id="default" value="1" id="defaultCheck1" checked>
                                <label class="form-check-label" for="defaultCheck1">{{__('file.Active')}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" name="action_button" id="submitButton" class="btn btn-primary">@lang('file.Submit')</button>
                    </div>
                </form>
            </div>
      </div>
    </div>
  </div>



