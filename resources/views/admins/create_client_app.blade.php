@extends('layouts.admin')
@section('Logo') <b>Admin - Employee</b>@endsection
@section('Datatable')
    <script src="{{ asset ("/js/admin/create_client_app.js") }}" type="text/javascript"></script>
@endsection

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
                    <h3 class="box-title">{{ __('create_client_app.title_create_client_app') }}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ route('create_client_app') }}" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group client-name @if (Session::get('error_client_name')) has-error @endif">
                            <label for="exampleInputEmail1">{{ __('create_client_app.client_name') }}</label>
                            <input type="text" name="client_name" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block"> @if (Session::get('error_client_name')) {{ Session::get('error_client_name') }} @endif</span>
                        </div>
                        <div class="form-group url-redirect @if (Session::get('error_url_redirect')) has-error @endif">
                            <label for="exampleInputPassword1">{{ __('create_client_app.client_call_back') }}</label>
                            <input type="text" name="url_redirect" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block">@if (Session::get('error_url_redirect')) {{ Session::get('error_url_redirect') }} @endif</span>
                        </div>
                        <div class="form-group ip-secure @if (Session::get('error_ip_secure')) has-error @endif" >
                            <label for="exampleInputPassword1">{{ __('create_client_app.ip_secure') }}</label>
                            <input type="text" name="ip_secure" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block"> @if (Session::get('error_ip_secure')) {{ Session::get('error_ip_secure') }} @endif</span>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-change-password">{{ __('client_app_setting.btn_create_new_client_app') }}</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </section><!-- /.content -->
@endsection