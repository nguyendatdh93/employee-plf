@extends('admins.layouts.admin')

@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('add_user.box_title_edit_user') }}</h3>
                    </div>
                    <!-- /.box-header -->

                    <form class="form-horizontal" method="POST" action="{{ route('edit-user') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('add_user.form_label_name') }}</label>

                                <div class="col-sm-9">
                                    <label class="control-label">{{ $user->name }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('add_user.form_label_email') }}</label>

                                <div class="col-sm-9">
                                    <label class="control-label">{{ $user->email }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="client_apps" class="col-sm-3 control-label">{{ trans('add_user.form_label_client_apps') }}</label>

                                <div class="col-sm-9">
                                    @foreach($client_apps as $app_id => $client_app)
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="client_apps[]" value="{{ $app_id }}">
                                                {{ $client_app }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ trans('add_user.btn_update_user') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </form>
                </div>
            </div>
        </div>
    </section><!-- /.content -->
@endsection