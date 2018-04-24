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
                    <h3 class="box-title">{{ __('edit_client_app.title_edit_client_app') }}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('edit_client_app') }}" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <input type="hidden" name="client_id" value="{{ $oauth_client[0]->id }}">
                        <div class="form-group client-name @if ($errors->has('client_name')) has-error @endif">
                            <label for="exampleInputEmail1" class="col-sm-2 control-label">{{ __('edit_client_app.client_name') }} <span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" value="{{ old('client_name', $oauth_client[0]->name) }}" name="client_name" class="form-control" id="exampleInputPassword1" placeholder="" required autofocus>
                                <span class="help-block"> @if ($errors->has('client_name')) {{ $errors->first('client_name') }} @endif</span>
                            </div>
                        </div>
                        <div class="form-group url-redirect @if ($errors->has('url_redirect')) has-error @endif">
                            <label for="exampleInputPassword1" class="col-sm-2 control-label">{{ __('edit_client_app.client_call_back') }} <span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" value="{{ old('url_redirect', $oauth_client[0]->redirect) }}" name="url_redirect" class="form-control" id="exampleInputPassword1" placeholder="" required autofocus>
                                <span class="help-block">@if ($errors->has('url_redirect')) {{ $errors->first('url_redirect') }} @endif</span>
                            </div>
                        </div>
                        <div class="form-group ip-secure @if ($errors->has('ip_secure')) has-error @endif" >
                            <label for="exampleInputPassword1" class="col-sm-2 control-label">{{ __('edit_client_app.ip_secure') }}</label>
                            <div class="col-sm-10">
                                <input type="text" value="{{ old('ip_secure', $oauth_client[0]->ip_secure) }}" name="ip_secure" class="form-control" id="exampleInputPassword1" placeholder="">
                                <span class="help-block"> @if ($errors->has('ip_secure')) {{ $errors->first('ip_secure') }} @endif</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary btn-change-password">{{ __('edit_client_app.btn_edit_client_app') }}</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </section><!-- /.content -->
@endsection

@include('admins.partials.validate_input_client_app')