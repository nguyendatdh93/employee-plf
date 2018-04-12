@extends('layouts.admin')
@section('Logo') <b>Admin - Employee</b>@endsection
@section('Datatable')
    <script src="{{ asset ("/js/admin/create_client_app.js") }}" type="text/javascript"></script>
@endsection

@section('Content')
    <!-- Main content -->
    <section class="content">
        @if (session('success'))
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-body bg-success">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('client_app_setting.title_client_app_setting') }}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ route('create_client_app') }}" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group client-name @if (Session::get('error_client_name')) has-error @endif">
                            <label for="exampleInputEmail1">{{ __('client_app_setting.client_name') }}</label>
                            <input type="text" name="client_name" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block"> @if (Session::get('error_client_name')) {{ Session::get('error_client_name') }} @endif</span>
                        </div>
                        <div class="form-group url-redirect @if (Session::get('error_url_redirect')) has-error @endif">
                            <label for="exampleInputPassword1">{{ __('client_app_setting.client_call_back') }}</label>
                            <input type="text" name="url_redirect" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block">@if (Session::get('error_url_redirect')) {{ Session::get('error_url_redirect') }} @endif</span>
                        </div>
                        <div class="form-group ip-secure @if (Session::get('error_ip_secure')) has-error @endif" >
                            <label for="exampleInputPassword1">{{ __('client_app_setting.ip_secure') }}</label>
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
        <div class="col-md-12 ">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">User managerment</h3>
                    <a href="{{ route('add_user_form') }}" class="btn bg-olive btn-flat margin pull-right"> <i class="fa fa-fw fa-user-plus"></i> {{ __('user_managerment.add_user') }}</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{  __('user_managerment.id') }}</th>
                            <th>{{  __('user_managerment.name') }}</th>
                            <th>{{  __('user_managerment.email') }}</th>
                            <th>{{  __('user_managerment.client_app') }}</th>
                            <th>{{  __('user_managerment.control') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </section><!-- /.content -->
@endsection