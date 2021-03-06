@extends('layouts.admin')
@section('Logo') <b>{{ __('logo.admin_site') }}</b>@endsection
@section('Datatable')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $('#datatable').DataTable({
                "language": {
                    "url" : "{{ route('lang') }}"
                },
            });
        })
    </script>
@endsection

@section('Content')
    @include('layouts.partials.modal')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12 " style="overflow: auto">
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="margin-top: 19px">{{ __('client_app_setting.title_client_app_setting') }}</h3>
                        <a href="{{ route('create_client_app_form') }}" class="btn bg-olive btn-flat margin pull-right"> <i class="fa fa-desktop"></i> {{ __('client_app_setting.btn_create_new_client_app') }}</a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="50">
                                <col width="100">
                                <col width="250">
                                <col width="100">
                                <col width="200" style="width: 300px !important;">
                                <col width="100">
                                <col width="100">
                            </colgroup>
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
                                    <td>
                                        @foreach($oauth_client->ip_secure as $ip)
                                            <p>{{ $ip }}</p>
                                        @endforeach
                                    </td>
                                    <td>{{ $oauth_client->redirect }}</td>
                                    <td>{{ $oauth_client->created_at }}</td>
                                    <td>
                                        <a href="{{ route('edit_client_app_form', ['client_app_id' => $oauth_client->id ]) }}" class="col-md-3 col-sm-4 btn-edit" data-toggle="tooltip" data-placement="top" title="{{  __('user_management.btn_edit') }}">
                                            <i class="fa fa-fw fa-edit" style="font-size: 20px"></i>
                                        </a>
                                        <a style="margin-left: 10px" href="{{ route('remove_client_app', ['client_app_id' => $oauth_client->id ]) }}"
                                           data-client-name="{{ $oauth_client->name }}"
                                           data-client-id="{{ $oauth_client->id }}"
                                           data-client-secret="{{ $oauth_client->secret }}"
                                           class="col-md-3 col-sm-4 btn-remove-client-app"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           title="{{  __('user_management.btn_remove') }}">
                                            <i class="fa fa-trash-o" style="font-size: 20px; color: darkred;"></i>
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
        </div>
    </section><!-- /.content -->
@endsection

@section('more_javascripts')
    <script>
        $(document).on('click', '.btn-remove-client-app', function(e){
            e.preventDefault();
            var url             = $(this).attr('href'),
                confirm_box     = $('#confirm'),
                client_id       = $(this).data('client-id'),
                client_name     = $(this).data('client-name'),
                client_secret   = $(this).data('client-secret'),
                confirm_message = '<p>{{ trans('client_app_setting.delete_confirm_text') }}</p>';

            confirm_message += '<br>{{ trans('client_app_setting.client_name') }}: ' + client_name;
            confirm_message += '<br>{{ trans('client_app_setting.client_id') }}: ' + client_id;
            confirm_message += '<br>{{ trans('client_app_setting.client_secret') }}: ' + client_secret;
            confirm_box.find('.modal-title').html('{{ trans('client_app_setting.delete_confirm_title') }}');
            confirm_box.find('.modal-body').html(confirm_message);
            confirm_box.find('#confirm-btn').html('{{ trans('client_app_setting.btn_confirm') }}');
            confirm_box.find('#cancel-btn').html('{{ trans('client_app_setting.btn_cancel') }}');
            confirm_box.modal({ backdrop: 'static', keyboard: false })
                .on('click', '#confirm-btn', function(){
                    window.location.replace(url);
                });
        });
    </script>
@endsection