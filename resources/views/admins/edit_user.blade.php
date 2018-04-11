@extends('layouts.admin')

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

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('edit_user.box_title_edit_user') }}</h3>
                    </div>
                    <!-- /.box-header -->

                    <form class="form-horizontal" method="POST" action="{{ route('edit_user') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('edit_user.form_label_name') }}</label>

                                <div class="col-sm-9">
                                    <label class="control-label">{{ $user->name }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('edit_user.form_label_email') }}</label>

                                <div class="col-sm-9">
                                    <label class="control-label">{{ $user->email }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="client_apps" class="col-sm-3 control-label">{{ trans('edit_user.form_label_client_apps') }}</label>

                                <div class="col-sm-9">
                                    @foreach($client_apps as $client_app)
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="client_apps[]" value="{{ $client_app->id }}" {{ in_array($client_app->id, $client_ids) ? 'checked' : '' }}>
                                                {{ $client_app->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ trans('edit_user.btn_update_user') }}
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