@extends('admins.layouts.admin')

@section('Datatable')
    <script>
        $(function () {
            $('#datatable').DataTable()
        })
    </script>
@endsection

@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 ">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">User managerment</h3>
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
                                    <a class="btn btn-app">
                                        <i class="fa fa-edit"></i>{{  __('user_managerment.btn_edit') }}
                                    </a>
                                    <a class="btn btn-app">
                                        <i class="fa fa-trash-o"></i>{{  __('user_managerment.btn_remove') }}
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