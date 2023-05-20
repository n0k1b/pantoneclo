@if (!Auth::check())
    <div class="custom-control custom-checkbox mt-5" >
        <input type="checkbox" class="custom-control-input" data-bs-toggle="collapse" href="#create_account_collapse" role="button" aria-expanded="false" aria-controls="create_account_collapse" name="billing_create_account_check" id="billing_create_account_check" value="1">
        <label class="label">@lang('file.Create Account')</label>
    </div>

    <div class="collapse" id="create_account_collapse">
        <input class="form-control mt-3 @error('username') is-invalid @enderror" value="{{ old('username') }}" type="text" placeholder="@lang('file.Enter Username')" name="username">
        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <input class="form-control mt-3 @error('password') is-invalid @enderror" type="password" placeholder="@lang('file.Enter Password')" name="password">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <input class="form-control mt-3 @error('password_confirmation') is-invalid @enderror" type="password" placeholder="@lang('file.Enter Confirm Password')" name="password_confirmation">
        @error('password_confirmation')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
@endif
