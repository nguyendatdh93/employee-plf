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

                <div class="row">
                    <div class="col-md-12 " style="overflow: auto">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title" style="margin-top: 19px;">{{ __('user_management.title') }}</h3>
                                <a href="{{ route('add_user_form') }}" class="btn bg-olive btn-flat margin pull-right"> <i class="fa fa-fw fa-user-plus"></i> {{ __('user_management.add_user') }}</a>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        {{--<th>{{  __('user_management.id') }}</th>--}}
                                        <th>{{  __('user_management.name') }}</th>
                                        <th>{{  __('user_management.email') }}</th>
                                        <th>{{  __('user_management.client_app') }}</th>
                                        <th style="width: 10%">{{  __('user_management.control') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list_users as $user)
                                        <tr>
                                            {{--<td>{{ $user->id }}</td>--}}
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach($user->client_apps as $client_app)
                                                    <p>- {{ $client_app->name }}</p>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ route('edit_user_form',['user_id' => $user->id]) }}" class="col-md-3 col-sm-4 btn-edit" data-toggle="tooltip" data-placement="top" title="{{  __('user_management.btn_edit') }}">
                                                    <i class="fa fa-fw fa-edit" style="font-size: 20px"></i>
                                                </a>
                                                <a href="{{ route('remove-user',['user_id' => $user->id, 'user_email' => $user->email]) }}"
                                                   class="col-md-3 col-sm-4 btn-remove jsRemove"
                                                   data-toggle="tooltip"
                                                   data-placement="top"
                                                   data-user-name="{{ $user->name }}"
                                                   data-user-email="{{ $user->email }}"
                                                   title="{{  __('user_management.btn_remove') }}" style="margin-left: 10px">
                                                    <i class="fa fa-trash-o" style="font-size: 20px; color: darkred;"></i>
                                                </a>
                                                @if($user->is_expired)
                                                    <a href="{{ route('reset_expired_user', ['id' => $user->id]) }}"
                                                       class="col-md-3 col-sm-4 btn-edit"
                                                       data-toggle="tooltip"
                                                       data-placement="top"
                                                       title="{{  __('user_management.btn_reset_expired') }}">
                                                        <i class="fa fa-fw fa-refresh" style="font-size: 20px; color: yellowgreen;"></i>
                                                    </a>
                                                @endif
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
            </div>
        </div>

    </section><!-- /.content -->
@endsection

@section('more_javascripts')
    <script>
        $(document).on('click', '.jsRemove', function(e){
            e.preventDefault();
            var url             = $(this).attr('href'),
                confirm_box     = $('#confirm'),
                user_name       = $(this).data('user-name'),
                user_email      = $(this).data('user-email'),
                confirm_message = '<p>{{ trans('user_management.delete_confirm_text') }}</p>';

            confirm_message += '{{ trans('user_management.name') }}: ' + user_name;
            confirm_message += '<br>{{ trans('user_management.email') }}: ' + user_email;
            confirm_box.find('.modal-title').html('{{ trans('user_management.delete_confirm_title') }}');
            confirm_box.find('.modal-body').html(confirm_message);
            confirm_box.find('#confirm-btn').html('{{ trans('user_management.btn_confirm') }}');
            confirm_box.find('#cancel-btn').html('{{ trans('user_management.btn_cancel') }}');
            confirm_box.modal({ backdrop: 'static', keyboard: false })
                .on('click', '#confirm-btn', function(){
                    window.location.replace(url);
                });
        });
    </script>
@endsection