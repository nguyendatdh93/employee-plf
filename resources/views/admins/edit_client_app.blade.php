@extends('layouts.admin')
@section('Logo') <b>{{ __('logo.admin_site') }}</b>@endsection

@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="col-md-6">
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
                <form action="{{ route('edit_client_app') }}" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <input type="hidden" name="client_id" value="{{ $oauth_client[0]->id }}">
                        <div class="form-group client-name @if (Session::get('error_client_name')) has-error @endif">
                            <label for="exampleInputEmail1">{{ __('edit_client_app.client_name') }}</label>
                            <input type="text" value="{{ $oauth_client[0]->name }}" name="client_name" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block"> @if (Session::get('error_client_name')) {{ Session::get('error_client_name') }} @endif</span>
                        </div>
                        <div class="form-group url-redirect @if (Session::get('error_url_redirect')) has-error @endif">
                            <label for="exampleInputPassword1">{{ __('edit_client_app.client_call_back') }}</label>
                            <input type="text" value="{{ $oauth_client[0]->redirect }}" name="url_redirect" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block">@if (Session::get('error_url_redirect')) {{ Session::get('error_url_redirect') }} @endif</span>
                        </div>
                        <div class="form-group ip-secure @if (Session::get('error_ip_secure')) has-error @endif" >
                            <label for="exampleInputPassword1">{{ __('edit_client_app.ip_secure') }}</label>
                            <input type="text" value="{{ $oauth_client[0]->ip_secure }}" name="ip_secure" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block"> @if (Session::get('error_ip_secure')) {{ Session::get('error_ip_secure') }} @endif</span>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-change-password">{{ __('edit_client_app.btn_edit_client_app') }}</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </section><!-- /.content -->
@endsection

@include('admins.partials.validate_input_client_app')