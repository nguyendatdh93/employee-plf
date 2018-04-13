@extends('layouts.user')
@section('Script')
    <script src="{{ asset ("/js/user/change_password.js") }}" type="text/javascript"></script>
@endsection
@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12">
            <div class="col-md-8 col-md-offset-2">
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
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('change_password.title_change_password') }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{ route('change_password') }}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body box-change-password">
                            <div class="form-group current-password @if (Session::get('error_current_password')) has-error @endif">
                                <label for="exampleInputEmail1">{{ __('change_password.current_password') }}</label>
                                <input type="password" name="current_password" class="form-control" id="exampleInputPassword1" placeholder="{{ __('change_password.current_password') }}">
                                <span class="help-block"> @if (Session::get('error_current_password')) {{ Session::get('error_current_password') }} @endif</span>
                            </div>
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

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-change-password">{{ __('change_password.btn_change_password') }}</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
@endsection