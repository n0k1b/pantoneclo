@push('css')
    <link rel="preload" href="http://demo.lion-coders.com/soft/sarchholm/css/bootstrap-colorpicker.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="http://demo.lion-coders.com/soft/sarchholm/css/bootstrap-colorpicker.css" rel="stylesheet"></noscript>
    <style>
        #switcher {list-style: none;margin: 0;padding: 0;overflow: hidden;}#switcher li {float: left;width: 30px;height: 30px;margin: 0 15px 15px 0;border-radius: 3px;}#demo {border-right: 1px solid #d5d5d5;width: 250px;height: 100%;left: -250px;position: fixed;padding: 50px 30px;background-color: #fff;transition: all 0.3s;z-index: 999;}#demo.open {left: 0;}.demo-btn {background-color: #fff;border: 1px solid #d5d5d5;border-left: none;border-bottom-right-radius: 3px;border-top-right-radius: 3px;color: var(--theme-color);font-size: 30px;height: 40px;position: absolute;right: -40px;text-align: center;top: 35%;width: 40px;}
    </style>
@endpush


<div class="card">
    <h3 class="card-header p-3"><b>@lang('file.Mail')</b></h3>
    <hr>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <form id="mailSubmit">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Mail From Address')</b></label>
                        <div class="col-sm-8">
                            <input type="email" name="mail_address" class="form-control" @isset($setting_mail->mail_address) value="{{$setting_mail->mail_address}}" @endisset>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Mail From Name')  <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <input type="text" name="mail_name" class="form-control" value="{{env('MAIL_FROM_NAME')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Mail Host') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <input type="text" name="mail_host" class="form-control" @isset($setting_mail->mail_host) value="{{$setting_mail->mail_host}}" @endisset>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Mail Port') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <input type="text" name="mail_port" class="form-control" @isset($setting_mail->mail_port) value="{{$setting_mail->mail_port}}" @endisset>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Mail Username') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <input type="text" name="mail_username" class="form-control" @isset($setting_mail->mail_username) value="{{$setting_mail->mail_username}}" @endisset>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Mail Password') <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <input type="password" required name="mail_password" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Mail Encryption')  <span class="text-danger">*</span></b></label>
                        <div class="col-sm-8">
                            <select name="mail_encryption" class="form-control">
                                <option value="">@lang('file.-- Select Encryption --')</option>
                                <option value="tls" @isset($setting_mail->mail_encryption) {{$setting_mail->mail_encryption=="tls" ? 'selected':''}} @endisset>TLS</option>
                                <option value="ssl" @isset($setting_mail->mail_encryption) {{$setting_mail->mail_encryption=="ssl" ? 'selected':''}} @endisset>SSL</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Template Header Color')</b></label>
                        <div class="col-sm-6">
                            <h6>Color Presets</h6>
                            <ul id="switcher" class="theme-color-header">
                                <li class="color-change" data-color="#6449e7" style="background-color:#6449e7"></li>
                                <li class="color-change" data-color="#f51e46" style="background-color:#f51e46"></li>
                                <li class="color-change" data-color="#fa9928" style="background-color:#fa9928"></li>
                                <li class="color-change" data-color="#fd6602" style="background-color:#fd6602"></li>
                                <li class="color-change" data-color="#59b210" style="background-color:#59b210"></li>
                                <li class="color-change" data-color="#ff749f" style="background-color:#ff749f"></li>
                                <li class="color-change" data-color="#f8008c" style="background-color:#f8008c"></li>
                                <li class="color-change" data-color="#6453f7" style="background-color:#6453f7"></li>
                            </ul>

                            <h6>@lang('file.Custom color')</h6>
                            <input type="text" id="color-input-header" @isset($setting_mail->mail_header_theme_color) value="{{$setting_mail->mail_header_theme_color}}" @endisset name="mail_header_theme_color" class="form-control colorpicker-element" data-colorpicker-id="1" title="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Template Body Color')</b></label>
                        <div class="col-sm-6">
                            <h6>Color Presets</h6>
                            <ul id="switcher" class="theme-color-body">
                                <li class="color-change" data-color="#6449e7" style="background-color:#6449e7"></li>
                                <li class="color-change" data-color="#f51e46" style="background-color:#f51e46"></li>
                                <li class="color-change" data-color="#fa9928" style="background-color:#fa9928"></li>
                                <li class="color-change" data-color="#fd6602" style="background-color:#fd6602"></li>
                                <li class="color-change" data-color="#59b210" style="background-color:#59b210"></li>
                                <li class="color-change" data-color="#ff749f" style="background-color:#ff749f"></li>
                                <li class="color-change" data-color="#f8008c" style="background-color:#f8008c"></li>
                                <li class="color-change" data-color="#6453f7" style="background-color:#6453f7"></li>
                            </ul>

                            <h6>@lang('file.Custom color')</h6>
                            <input type="text" id="color-input-body" @isset($setting_mail->mail_body_theme_color) value="{{$setting_mail->mail_body_theme_color}}" @endisset name="mail_body_theme_color" class="form-control colorpicker-element" data-colorpicker-id="1" title="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Template Footer Color')</b></label>
                        <div class="col-sm-6">
                            <h6>Color Presets</h6>
                            <ul id="switcher" class="theme-color-footer">
                                <li class="color-change" data-color="#6449e7" style="background-color:#6449e7"></li>
                                <li class="color-change" data-color="#f51e46" style="background-color:#f51e46"></li>
                                <li class="color-change" data-color="#fa9928" style="background-color:#fa9928"></li>
                                <li class="color-change" data-color="#fd6602" style="background-color:#fd6602"></li>
                                <li class="color-change" data-color="#59b210" style="background-color:#59b210"></li>
                                <li class="color-change" data-color="#ff749f" style="background-color:#ff749f"></li>
                                <li class="color-change" data-color="#f8008c" style="background-color:#f8008c"></li>
                                <li class="color-change" data-color="#6453f7" style="background-color:#6453f7"></li>
                            </ul>

                            <h6>@lang('file.Custom color')</h6>
                            <input type="text" id="color-input-footer" @isset($setting_mail->mail_footer_theme_color) value="{{$setting_mail->mail_footer_theme_color}}" @endisset name="mail_footer_theme_color" class="form-control colorpicker-element" data-colorpicker-id="1" title="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Template Background Color')</b></label>
                        <div class="col-sm-6">
                            <h6>Color Presets</h6>
                            <ul id="switcher" class="theme-color-background">
                                <li class="color-change" data-color="#6449e7" style="background-color:#6449e7"></li>
                                <li class="color-change" data-color="#f51e46" style="background-color:#f51e46"></li>
                                <li class="color-change" data-color="#fa9928" style="background-color:#fa9928"></li>
                                <li class="color-change" data-color="#fd6602" style="background-color:#fd6602"></li>
                                <li class="color-change" data-color="#59b210" style="background-color:#59b210"></li>
                                <li class="color-change" data-color="#ff749f" style="background-color:#ff749f"></li>
                                <li class="color-change" data-color="#f8008c" style="background-color:#f8008c"></li>
                                <li class="color-change" data-color="#6453f7" style="background-color:#6453f7"></li>
                            </ul>

                            <h6>@lang('file.Custom color')</h6>
                            <input type="text" id="color-input-background" @isset($setting_mail->mail_layout_background_theme_color) value="{{$setting_mail->mail_layout_background_theme_color}}" @endisset name="mail_layout_background_theme_color" class="form-control colorpicker-element" data-colorpicker-id="1" title="">
                        </div>
                    </div>

                    <div class="form-check mt-3 mb-3">
                        <input class="form-check-input" name="send_mail_check" type="checkbox" value="1" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <label class="form-check-label" for="flexCheckDefault">
                            <b>Send me a mail for testing</b>
                        </label>
                    </div>

                    <div class="collapse m-3" id="collapseExample">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>Send to Mail</b></label>
                            <div class="col-sm-8">
                                <input class="form-control" name="mail_to" type="text" placeholder="example@gmail.com" name="mail_to">
                            </div>
                        </div>
                    </div>


                    {{--
                    <br>
                    <h3 class="text-bold">@lang('file.Customer Notification Settings')</h3>
                    <br>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label"><b>@lang('file.Welcome Email')</b></label>
                        <div class="col-sm-8">
                            <div class="form-check mt-1">
                                <input type="checkbox" value="1" name="welcome_email" class="form-check-input" @isset($setting_mail->welcome_email) {{$setting_mail->welcome_email=="1" ? 'checked':''}} @endisset>
                                <label class="p-0 form-check-label" for="exampleCheck1">@lang('file.Send welcome email after registration')</label>
                            </div>
                        </div>
                    </div>

                    <br>
                    <h3 class="text-bold">@lang('file.Order Notification Settings')</h3>
                    <br>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label"><b>@lang('file.New Order Admin Email')</b></label>
                        <div class="col-sm-8">
                            <div class="form-check mt-1">
                                <input type="checkbox" value="1" name="new_order_to_admin" class="form-check-input" @isset($setting_mail->new_order_to_admin) {{$setting_mail->new_order_to_admin=="1" ? 'checked':''}} @endisset>
                                <label class="p-0 form-check-label" for="exampleCheck1">@lang('file.Send new order notification to the admin')</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label"><b>@lang('file.Invoice Email')</b></label>
                        <div class="col-sm-8">
                            <div class="form-check mt-1">
                                <input type="checkbox" value="1" name="invoice_mail" class="form-check-input" @isset($setting_mail->invoice_mail) {{$setting_mail->invoice_mail=="1" ? 'checked':''}} @endisset>
                                <label class="p-0 form-check-label" for="exampleCheck1">@lang('file.Send invoice email to the customer after checkout')</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><b>@lang('file.Email Order Status')</b></label>
                        <div class="col-sm-8">
                            <select name="mail_order_status" class="form-control">
                                <option value="">@lang('file.-- Select Status --')</option>
                                <option value="canceled" @isset($setting_mail->mail_order_status) {{$setting_mail->mail_order_status=="canceled" ? 'selected':''}} @endisset>{{ucfirst("canceled")}}</option>
                                <option value="completed" @isset($setting_mail->mail_order_status) {{$setting_mail->mail_order_status=="completed" ? 'selected':''}} @endisset>{{ucfirst("completed")}}</option>
                                <option value="on_hold" @isset($setting_mail->mail_order_status) {{$setting_mail->mail_order_status=="on_hold" ? 'selected':''}} @endisset>{{ucfirst("on hold")}}</option>
                                <option value="pending" @isset($setting_mail->mail_order_status) {{$setting_mail->mail_order_status=="pending" ? 'selected':''}} @endisset>{{ucfirst("pending payment")}}</option>
                                <option value="processing" @isset($setting_mail->mail_order_status) {{$setting_mail->mail_order_status=="processing" ? 'selected':''}} @endisset>{{ucfirst("processing payment")}}</option>
                                <option value="refunded" @isset($setting_mail->mail_order_status) {{$setting_mail->mail_order_status=="refunded" ? 'selected':''}} @endisset>{{ucfirst("refunded")}}</option>
                            </select>
                        </div>
                    </div> --}}


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


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js"></script>
    <script>
        $('#color-input-header').colorpicker({});
        $('#color-input-body').colorpicker({});
        $('#color-input-footer').colorpicker({});
        $('#color-input-background').colorpicker({});

        $('.theme-color-header .color-change').click(function() {
            var color = $(this).data('color');
            $('#color-input-header').val(color);
        });
        $('.theme-color-body .color-change').click(function() {
            var color = $(this).data('color');
            $('#color-input-body').val(color);
        });
        $('.theme-color-footer .color-change').click(function() {
            var color = $(this).data('color');
            $('#color-input-footer').val(color);
        });
        $('.theme-color-background .color-change').click(function() {
            var color = $(this).data('color');
            $('#color-input-background').val(color);
        });
    </script>
@endpush
