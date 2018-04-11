@extends('layouts.admin')
@section('Logo') <b>Admin - Employee</b>@endsection
@section('Datatable')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
            $('#datatable').DataTable()
        })
    </script>
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
                        @foreach($list_users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->client_apps as $client_app)
                                        <p>{{ $client_app->name }}</p>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('edit_user_form',['user_id' => $user->id]) }}" class="col-md-3 col-sm-4 btn-edit" data-toggle="tooltip" data-placement="top" title="{{  __('user_managerment.btn_edit') }}">
                                        <i class="fa fa-fw fa-edit" style="font-size: 20px"></i>
                                    </a>
                                    <a href="{{ route('remove-user',['user_id' => $user->id]) }}" class="col-md-3 col-sm-4 btn-remove" data-toggle="tooltip" data-placement="top" title="{{  __('user_managerment.btn_remove') }}">
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