@extends('layouts.user')
@section('Script')
    <script src="{{ asset ("/js/user/reset_password.js") }}" type="text/javascript"></script>
@endsection
@section('Body')
    <div class="register-box">
        @if (session('error_change_password'))
            <div class="alert alert-error">
                {{ session('error_change_password') }}
            </div>
        @endif
        <div class="register-box-body">
            <p class="login-box-msg">You must change your password on the frist time.</p>

            <form action="{{ route('reset_password') }}" method="post">
                {{ csrf_field() }}
                <div class="box-body">
                    <input type="hidden" name="reset_password" value="1" />
                    <div class="form-group new-password @if (Session::get('error_new_password')) has-error @endif">
                        <label for="exampleInputPassword1">{{ __('change_password.new_password') }}</label>
                        <input type="password" name="new_password" class="form-control" id="exampleInputPassword1" placeholder="{{ __('change_password.new_password') }}">
                        <span class="help-block">@if (Session::get('error_new_password')) {{ Session::get('error_new_password') }} @endif</span>
                    </div>
                    <div class="form-group confirm-new-password @if (Session::get('error_confirm_new_password')) has-error @endif" >
                        <label for="exampleInputPassword1">{{ __('change_password.confirm_new_password') }}</label>
                        <input type="password" name="confirm_new_password" class="form-control" id="exampleInputPassword1" placeholder="{{ __('change_password.confirm_new_password') }}">
                        <span class="help-block"> @if (Session::get('error_confirm_new_password')) {{ Session::get('error_confirm_new_password') }} @endif</span>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer" style="text-align: center;">
                    <button type="submit" class="btn btn-primary btn-change-password">{{ __('change_password.btn_change_password') }}</button>
                </div>
            </form>
        </div>
        <!-- /.form-box -->
    </div>
@endsection