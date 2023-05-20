@extends('admin.main')
@section('title', 'Admin | Products')

@section('admin_content')


    <section>
        <div class="container-fluid mb-3">
            <h3 class="font-weight-bold mt-3">@lang('file.Attribute Wise Inventory')</h3>
        </div>

        <div class="container-fluid">

            <div class="row">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" readonly value="White">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" readonly value="L">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" data-toggle="collapse" href="#collapseExample"
                                                    role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <label class="p-0 form-check-label"
                                                    for="exampleCheck1">@lang('file.Show')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="collapse" id="collapseExample">
                                    <div class="card card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label><strong>Stock Status</strong></label>
                                                <select name="stock_status" class="form-control selectpicker" title='Select Status'>
                                                    <option value="stock_in">Stock In</option>
                                                    <option value="stock_out">Stock Out</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputEmail4"><strong>SKU</strong></label>
                                                <input type="text" class="form-control" id="inputEmail4" placeholder="SKU">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputPassword4"><strong>Price</strong></label>
                                                <input type="text" class="form-control" id="inputPassword4" placeholder="Price">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputPassword4"><strong>Weight</strong></label>
                                                <input type="text" class="form-control" id="inputPassword4" placeholder="Price">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputPassword4"><strong>Dimention</strong></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="Length">
                                                    <input type="text" class="form-control" placeholder="Width">
                                                    <input type="text" class="form-control" placeholder="Height">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputPassword4"><strong>Image</strong></label>
                                                <input type="file" class="form-control">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputPassword4"><strong>Track Inventory</strong></label> <br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                  </div>
                                                  <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                                  </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputPassword4"><strong>Quantity</strong></label>
                                                <input type="number" min="0" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="White">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="M">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" value="1" name="" id=""
                                                    class="form-check-input">
                                                <label class="p-0 form-check-label"
                                                    for="exampleCheck1">@lang('file.Show')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="White">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="S">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" value="1" name="" id=""
                                                    class="form-check-input">
                                                <label class="p-0 form-check-label"
                                                    for="exampleCheck1">@lang('file.Show')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="Black">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="L">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" value="1" name="" id=""
                                                    class="form-check-input">
                                                <label class="p-0 form-check-label"
                                                    for="exampleCheck1">@lang('file.Show')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="Black">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="M">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" value="1" name="" id=""
                                                    class="form-check-input">
                                                <label class="p-0 form-check-label"
                                                    for="exampleCheck1">@lang('file.Show')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="Black">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="S">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" value="1" name="" id=""
                                                    class="form-check-input">
                                                <label class="p-0 form-check-label"
                                                    for="exampleCheck1">@lang('file.Show')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="Grey">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="L">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" value="1" name="" id=""
                                                    class="form-check-input">
                                                <label class="p-0 form-check-label"
                                                    for="exampleCheck1">@lang('file.Show')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="Grey">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="M">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" value="1" name="" id=""
                                                    class="form-check-input">
                                                <label class="p-0 form-check-label"
                                                    for="exampleCheck1">@lang('file.Show')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="Grey">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly value="S">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" value="1" name="" id=""
                                                    class="form-check-input">
                                                <label class="p-0 form-check-label"
                                                    for="exampleCheck1">@lang('file.Show')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    @endsection
