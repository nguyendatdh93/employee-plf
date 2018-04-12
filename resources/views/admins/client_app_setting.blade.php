@extends('layouts.admin')
@section('Logo') <b>Admin - Employee</b>@endsection
@section('Datatable')
    <script src="{{ asset ("/js/admin/client_app_setting.js") }}" type="text/javascript"></script>
@endsection

@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 ">
            @if (session('success'))
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('error') }}
                </div>
            @endif
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{ __('client_app_setting.title_client_app_setting') }}</h3>
                    <a href="{{ route('create_client_app_form') }}" class="btn bg-olive btn-flat margin pull-right"> <i class="fa fa-desktop"></i> {{ __('client_app_setting.btn_create_new_client_app') }}</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{  __('client_app_setting.client_id') }}</th>
                            <th>{{  __('client_app_setting.client_name') }}</th>
                            <th>{{  __('client_app_setting.client_secret') }}</th>
                            <th>{{  __('client_app_setting.ip_secure') }}</th>
                            <th>{{  __('client_app_setting.redirect_url') }}</th>
                            <th>{{  __('client_app_setting.created_at') }}</th>
                            <th>{{  __('client_app_setting.control') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($oauth_clients as $oauth_client)
                                <tr>
                                    <td>{{ $oauth_client->id }}</td>
                                    <td>{{ $oauth_client->name }}</td>
                                    <td>{{ $oauth_client->secret }}</td>
                                    <td>{{ $oauth_client->ip_secure }}</td>
                                    <td>{{ $oauth_client->redirect }}</td>
                                    <td>{{ $oauth_client->created_at }}</td>
                                    <td>
                                        <a href="{{ route('edit_client_app_form', ['client_app_id' => $oauth_client->id ]) }}" class="col-md-3 col-sm-4 btn-edit" data-toggle="tooltip" data-placement="top" title="{{  __('user_managerment.btn_edit') }}">
                                            <i class="fa fa-fw fa-edit" style="font-size: 20px"></i>
                                        </a>
                                        <a href="{{ route('remove_client_app', ['client_app_id' => $oauth_client->id ]) }}" class="col-md-3 col-sm-4 btn-remove-client-app" data-toggle="tooltip" data-placement="top" title="{{  __('user_managerment.btn_remove') }}">
                                            <i class="fa fa-trash-o" style="font-size: 20px"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </section><!-- /.content -->
@endsection