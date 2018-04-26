@extends('layouts.user')

@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('user_profile.box_title_user_profile') }}</h3>
                </div>
                <!-- /.box-header -->
                <form class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('user_profile.form_label_name') }}</label>

                            <div class="col-sm-9" style="padding: 6px">
                                <span>{{ $user->name }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('user_profile.form_label_email') }}</label>

                            <div class="col-sm-9" style="padding: 6px">
                                <span>{{ $user->email }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="client_apps" class="col-sm-3 control-label">{{ trans('user_profile.form_label_client_apps') }}</label>

                            <div class="col-sm-9" style="padding: 6px">
                                    @foreach($client_apps as $client_app)
                                        <p>{{ $client_app->name }}</p>
                                    @endforeach
                            </div>
                        </div>
                    </div>
                <!-- /.box-body -->
                </form>
            </div>
        </div>
    </section><!-- /.content -->
@endsection