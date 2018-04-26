@extends('layouts.user')
@section('Body')
    <div class="box box-primary register-box">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('success') }}
            </div>
        @endif
        <div class="register-box-body">
            <h4 class="login-box-msg">{{ __('reset_password.title_reset_password') }}</h4>

            <form action="{{ route('reset_password') }}" method="post">
                {{ csrf_field() }}
                <div class="box-body">
                    <input type="hidden" name="reset_password" value="1" />
                    <div class="form-group new-password @if ($errors->has('new_password')) has-error @endif">
                        <label for="exampleInputPassword1">{{ __('reset_password.new_password') }} <span class="required">*</span></label>
                        <input type="password" name="new_password" class="form-control" id="exampleInputPassword1" placeholder="{{ __('reset_password.new_password') }}">
                        <span class="help-block">@if ($errors->has('new_password')) {{ $errors->first('new_password') }} @endif</span>
                    </div>
                    <div class="form-group confirm-new-password @if ($errors->has('confirm_new_password')) has-error @endif" >
                        <label for="exampleInputPassword1">{{ __('reset_password.confirm_new_password') }} <span class="required">*</span></label>
                        <input type="password" name="confirm_new_password" class="form-control" id="exampleInputPassword1" placeholder="{{ __('reset_password.confirm_new_password') }}">
                        <span class="help-block"> @if ($errors->has('confirm_new_password')) {{ $errors->first('confirm_new_password') }} @endif</span>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer" style="text-align: center;">
                    <button type="submit" class="btn btn-primary btn-change-password">{{ __('reset_password.btn_change_password') }}</button>
                </div>
            </form>
        </div>
        <!-- /.form-box -->
    </div>
@endsection