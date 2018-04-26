@extends('layouts.user')
@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="col-md-8">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!! session('error')  !!}
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
                <form class="form-horizontal" action="{{ route('change_password') }}" method="post">
                    {{ csrf_field() }}
                    <div class="box-body box-change-password">
                        <div class="form-group current-password @if ($errors->has('current_password')) has-error @endif">
                            <label for="exampleInputEmail1" class="col-sm-2 control-label">{{ __('change_password.current_password') }} <span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="password" name="current_password" value="{{old('current_password')}}" class="form-control" id="exampleInputPassword1" placeholder="{{ __('change_password.current_password') }}">
                                <span class="help-block"> @if ($errors->has('current_password')) {{ $errors->first('current_password') }} @endif</span>
                            </div>
                        </div>
                        <div class="form-group new-password @if ($errors->has('new_password')) has-error @endif">
                            <label for="exampleInputPassword1" class="col-sm-2 control-label">{{ __('change_password.new_password') }} <span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="password" name="new_password" class="form-control" value="{{old('new_password')}}" id="exampleInputPassword1" placeholder="{{ __('change_password.new_password') }}">
                                <span class="help-block">@if ($errors->has('new_password')) {{ $errors->first('new_password') }} @endif</span>
                            </div>
                        </div>
                        <div class="form-group confirm-new-password @if ($errors->has('confirm_new_password')) has-error @endif" >
                            <label for="exampleInputPassword1" class="col-sm-2 control-label">{{ __('change_password.confirm_new_password') }} <span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="password" name="confirm_new_password" value="{{old('confirm_new_password')}}" class="form-control" id="exampleInputPassword1" placeholder="{{ __('change_password.confirm_new_password') }}">
                                <span class="help-block"> @if ($errors->has('confirm_new_password')) {{ $errors->first('confirm_new_password') }} @endif</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary btn-change-password">{{ __('change_password.btn_change_password') }}</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </section><!-- /.content -->
@endsection