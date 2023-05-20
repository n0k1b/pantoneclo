<div class="tab-pane fade show" aria-labelledby="product-seo" id="seo" role="tabpanel">
    <div class="card">
        <h4 class="card-header"><b>{{__('file.Weight')}}</b></h4>
        <hr>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <b> {{__('file.Weight')}} </b> <i>[KG]</i> <br>
                        </label>
                        <div class="col-sm-8">
                            <input name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" id="inputEmail3" placeholder="Type Weight Value (Ex: 10)" value="{{old('weight')}}">
                            @error('weight')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="weight_base_calculation" value="1">
                                <span><b>I want weight base calculation.</b></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12">
                            <div class="form-group form-check">
                                <i> <mark> Price will be calculated on weight basis. (1 KG= 1000 gm). On the other hand if your product depends on weight basis like hen, fruits, milk etc. then set <b>"1"</b> in weight input field and enable the checkbox because product will calculate 1 KG = 1000 gm basis and the attribute value should be assigned as like <b>"200 gm"</b>, <b>"500 gm"</b>, <b>"1000 gm"</b> etc.</mark> </i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
