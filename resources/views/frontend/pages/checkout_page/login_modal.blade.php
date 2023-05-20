<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="login-form" action="{{route('customer.login')}}" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                </div>
                <div class="row mt-4">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('customer.password.request') }}" tabindex="5" class="forgot-password theme-color">@lang('file.Forgot Password')</a>
                    </div>
                </div>
                <div class="form-group mt-4 mb-1">
                    <button type="submit" class="button style1 d-block text-center w-100">{{__('file.Log In')}}</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('file.Close')</button>
        </div>
    </div>
    </div>
</div>
