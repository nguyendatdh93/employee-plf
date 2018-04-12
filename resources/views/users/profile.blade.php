@extends('layouts.user')

@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @if (session('success'))
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('success') }}
                    </div>
                @endif
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('user_profile.box_title_user_profile') }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <form class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('user_profile.form_label_name') }}</label>

                            <div class="col-sm-9">
                                <label class="control-label">{{ $user->name }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('user_profile.form_label_email') }}</label>

                            <div class="col-sm-9">
                                <label class="control-label">{{ $user->email }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="client_apps" class="col-sm-3 control-label">{{ trans('user_profile.form_label_client_apps') }}</label>

                            <div class="col-sm-9">
                                @foreach($client_apps as $client_app)
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="control-label">{{ $client_app->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
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