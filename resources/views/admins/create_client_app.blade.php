@extends('layouts.admin')
@section('Logo') <b>{{ __('logo.admin_site') }}</b>@endsection

@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="col-md-8">
            @if (session('error'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
             @endif
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('create_client_app.title_create_client_app') }}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('create_client_app') }}" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group client-name @if ($errors->has('client_name')) has-error @endif">
                            <label for="exampleInputEmail1" class="col-sm-2 control-label">{{ __('create_client_app.client_name') }} <span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="client_name" class="form-control" value="{{old('client_name')}}" id="exampleInputPassword1" placeholder="" autofocus>
                                <span class="help-block"> @if ($errors->has('client_name')) {{ $errors->first('client_name') }} @endif</span>
                            </div>
                        </div>
                        <div class="form-group url-redirect @if ($errors->has('url_redirect')) has-error @endif">
                            <label for="exampleInputPassword1" class="col-sm-2 control-label">{{ __('create_client_app.client_call_back') }} <span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="url_redirect" class="form-control" value="{{old('url_redirect')}}" id="exampleInputPassword1" placeholder="ex: http://example.com/callback" autofocus>
                                <span class="help-block">@if ($errors->has('url_redirect')) {{ $errors->first('url_redirect') }} @endif</span>
                            </div>
                        </div>
                        <div class="form-group ip-secure @if ($errors->has('ip_secure')) has-error @endif" >
                            <label for="exampleInputPassword1" class="col-sm-2 control-label">{{ __('create_client_app.ip_secure') }} </label>
                            <div class="col-sm-10">
                                <input type="text" name="ip_secure" class="form-control" value="{{old('ip_secure')}}" id="exampleInputPassword1" placeholder="">
                                <span class="help-block"> @if ($errors->has('ip_secure')) {{ $errors->first('ip_secure') }} @endif</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary">{{ __('create_client_app.btn_create_new_client_app') }}</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </section><!-- /.content -->
@endsection

@include('admins.partials.validate_input_client_app')