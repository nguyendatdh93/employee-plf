@extends('layouts.user')
@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12">
            <div class="col-md-8 col-md-offset-2">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {!! session('error')  !!}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('success') }}
                    </div>
                @endif
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('change_password.title_change_password') }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{ route('change_password') }}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body box-change-password">
                            <div class="form-group current-password @if ($errors->has('current_password')) has-error @endif">
                                <label for="exampleInputEmail1">{{ __('change_password.current_password') }}</label>
                                <input type="password" name="current_password" class="form-control" id="exampleInputPassword1" placeholder="{{ __('change_password.current_password') }}">
                                <span class="help-block"> @if ($errors->has('current_password')) {{ $errors->first('current_password') }} @endif</span>
                            </div>
                            <div class="form-group new-password @if ($errors->has('new_password')) has-error @endif">
                                <label for="exampleInputPassword1">{{ __('change_password.new_password') }}</label>
                                <input type="password" name="new_password" class="form-control" id="exampleInputPassword1" placeholder="{{ __('change_password.new_password') }}">
                                <span class="help-block">@if ($errors->has('new_password')) {{ $errors->first('new_password') }} @endif</span>
                            </div>
                            <div class="form-group confirm-new-password @if ($errors->has('confirm_new_password')) has-error @endif" >
                                <label for="exampleInputPassword1">{{ __('change_password.confirm_new_password') }}</label>
                                <input type="password" name="confirm_new_password" class="form-control" id="exampleInputPassword1" placeholder="{{ __('change_password.confirm_new_password') }}">
                                <span class="help-block"> @if ($errors->has('confirm_new_password')) {{ $errors->first('confirm_new_password') }} @endif</span>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-change-password">{{ __('change_password.btn_change_password') }}</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
@endsection

@section('Script')
    <script>
        $(function () {
            $('.btn-change-password').click(function(){
                var current_password     = $('.current-password input').val();
                var new_password         = $('.new-password input').val();
                var confirm_new_password = $('.confirm-new-password input').val();

                if (current_password == '') {
                    enableError('current-password', '{{ __('change_password.fill_out') }}');

                    return false;
                } else {
                    disableError('current-password')
                }

                if (new_password == '') {
                    enableError('new-password', '{{ __('change_password.fill_out') }}');

                    return false;
                }else if (new_password.length < 8) {
                    enableError('new-password', '{{ __('change_password.lenght_8') }}');

                    return false;
                }else if (new_password.length > 50) {
                    enableError('new-password', '{{ __('change_password.lenght_50') }}');

                    return false;
                } else {
                    disableError('new-password')
                }

                if (confirm_new_password != new_password) {
                    enableError('confirm-new-password', '{{ __('change_password.not_matches_password') }}');

                    return false;
                } else {
                    disableError('confirm-new-password')
                }

                if (!validatePassword('new-password', new_password)) {

                    return false;
                }

                if (!validatePassword('confirm-new-password', confirm_new_password)) {
                    return false;
                }

                return true;
            })

            function enableError(classEl, message)
            {
                $('.'+classEl).addClass('has-error');
                $('.'+classEl + ' .help-block').text(message);
            }

            function disableError(classEl)
            {
                $('.'+classEl).removeClass('has-error');
                $('.'+classEl + ' .help-block').text('');
            }

            function validatePassword(classEl, password)
            {
                // Validate length
                if(password.length < 8) {
                    enableError(classEl, '{{ __('change_password.lenght_8') }}');

                    return false;
                }

                disableError(classEl);

                return true;
            }
        })
    </script>
@endsection