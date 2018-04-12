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
        <div class="col-md-6 ">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{ __('client_app_setting.title_client_app_setting') }}</h3>
                    <a href="{{ route('create_client_app_form') }}" class="btn bg-olive btn-flat margin pull-right"> <i class="fa fa-fw fa-user-plus"></i> {{ __('client_app_setting.btn_create_new_client_app') }}</a>
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