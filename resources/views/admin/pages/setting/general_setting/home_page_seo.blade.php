<style>
    textarea {
        resize: none;
    }

    #count_message {
    background-color: smoke;
    margin-top: -20px;
    margin-right: 5px;
    }
</style>

<div class="card">
    <h4 class="card-header p-3"><b>@lang('file.Home Page SEO Setup')</b></h4>
    <hr>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <form id="homePageSeoSubmit" action="{{route('admin.setting.home_page_seo.store_or_update')}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Site Name') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <input type="text" required name="meta_site_name" class="form-control" @empty(!$setting_home_page_seo) value="{{$setting_home_page_seo->meta_site_name}}" @endempty>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Meta Title') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <input type="text" required name="meta_title" class="form-control" @empty(!$setting_home_page_seo) value="{{$setting_home_page_seo->meta_title}}" @endempty>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Meta Description') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <textarea name="meta_description" id="text" required class="form-control" cols="30" rows="5" >@empty(!$setting_home_page_seo) {{$setting_home_page_seo->meta_description}} @endempty</textarea>
                            <span class="pull-right label label-default" id="count_message"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Meta URL') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <input type="text" name="meta_url" required class="form-control" @empty(!$setting_home_page_seo) value="{{$setting_home_page_seo->meta_url}}" @endempty>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Meta Type') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <input type="text" name="meta_type" required class="form-control" @empty(!$setting_home_page_seo) value="{{$setting_home_page_seo->meta_type}}" @endempty>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Meta Image') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            @if(!empty($setting_home_page_seo) && $setting_home_page_seo->meta_image!==null && Illuminate\Support\Facades\File::exists(public_path($setting_home_page_seo->meta_image)))
                                <img src="{{asset('public/'.$setting_home_page_seo->meta_image)}}" id="homePageSeoImage" height="100px" width="100px">
                            @else
                                <img src="https://dummyimage.com/100x100/cccccc/666666&text=Empty" id="homePageSeoImage">
                            @endif
                            <input type="file" name="meta_image" class="form-control" onchange="showImage(this,'homePageSeoImage')">
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8">
                            <button type="submit" class="btn btn-primary">@lang('file.Save')</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-2"></div>
        </div>

    </div>
</div>

@php
    $meta_description = isset($setting_home_page_seo->meta_description) ? $setting_home_page_seo->meta_description : null
@endphp

@push('scripts')
    <script type="text/javascript">
        (function ($) {
            "use strict";

                var myStr = '';
                var metaDescription = {!! json_encode($meta_description) !!};
                if (metaDescription) {
                    var myStr =  metaDescription;
                }


                var myStringCount = myStr.length;
                var text_max = 160;
                $('#count_message').html(myStringCount + ' / ' + text_max );

                $('#text').keyup(function() {
                    var text_length = $('#text').val().length;
                    var text_remaining = text_max - text_length;
                    $('#count_message').html(text_length + ' / ' + text_max);
                });

        })(jQuery);
    </script>
@endpush
