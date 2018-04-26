@extends('layouts.admin')
@section('Logo') <b>{{ __('logo.admin_site') }}</b>@endsection
@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                @if (!empty($sql))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-body bg-warning">
                                    {{ $sql }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <!-- /.box-header -->

                    <form class="form-horizontal" method="GET" action="{{ route('get_sql_add_admin_form') }}">
                        @csrf

                        <div class="box-body">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-sm-3 control-label">{{ trans('add_user.form_label_name') }} <span class="required">*</span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="{{ trans('add_user.placeholder_name') }}" autofocus>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-sm-3 control-label">{{ trans('add_user.form_label_email') }} <span class="required">*</span></label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('add_user.placeholder_email') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-sm-3 control-label">{{ trans('add_user.form_label_password') }}</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="password" name="password" value="{{ Config::get('base.default_password') }}">
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
        </div>
    </section><!-- /.content -->
@endsection