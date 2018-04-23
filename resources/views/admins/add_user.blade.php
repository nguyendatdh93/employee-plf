@extends('layouts.admin')
@section('Logo') <b>{{ __('logo.admin_site') }}</b>@endsection
@section('Content')
    <!-- Main content -->
    <section class="content">
        @if ($errors->has('messages'))
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-warning">
                        <div class="panel-body bg-warning">
                            {!! $errors->first('messages') !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('add_user.box_title_add_user') }}</h3>
                </div>
                <!-- /.box-header -->

                <form class="form-horizontal" method="POST" action="{{ route('add_user') }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-sm-2 control-label">{{ trans('add_user.form_label_name') }} <span class="required">*</span></label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="{{ trans('add_user.placeholder_name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-sm-2 control-label">{{ trans('add_user.form_label_email') }} <span class="required">*</span></label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('add_user.placeholder_email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group" hidden>
                            <label for="password" class="col-sm-2 control-label">{{ trans('add_user.form_label_password') }}</label>

                            <div class="col-sm-10">
                                <input type="text" disabled="disabled" class="form-control" id="password" name="password" value="{{ Config::get('base.default_password') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="client_apps" class="col-sm-2 control-label">{{ trans('add_user.form_label_client_apps') }}</label>

                            <div class="col-sm-10">
                                @foreach($client_apps as $client_app)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="client_apps[]" value="{{ $client_app->id }}" {{ !empty(old('client_apps')) && in_array($client_app->id, old('client_apps')) ? 'checked' : '' }}>
                                            {{ $client_app->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('add_user.btn_create_user') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>
    </section><!-- /.content -->
@endsection