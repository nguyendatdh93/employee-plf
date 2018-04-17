@extends('layouts.admin')
@section('Logo') <b>{{ __('logo.admin_site') }}</b>@endsection
@section('Datatable')
    <script>
        $(function () {
            $('.btn-change-password').click(function(){
                var client_name  = $('.client-name input').val();
                var url_redirect = $('.url-redirect input').val();
                var ip_secure    = $('.ip-secure input').val();

                if (client_name == '') {
                    enableError('client-name', '{{ __('create_client_app.fill_out') }}');

                    return false;
                } else if(client_name.length > 255){
                    enableError('client-name', '{{ __('create_client_app.lenght_255') }}');

                    return false;
                } else {
                    disableError('client-name')
                }

                if (url_redirect == '') {
                    enableError('url-redirect', '{{ __('create_client_app.fill_out') }}');

                    return false;
                } else if(url_redirect.length > 255){
                    enableError('url-redirect', '{{ __('create_client_app.lenght_255') }}');

                    return false;
                } else {
                    disableError('new-password')
                }

                if (!validateClientUrlRedirect('url-redirect',  $('.url-redirect input').val())) {
                    return false;
                }

                if (!validateIPaddress('ip-secure',  $('.ip-secure input').val())) {
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

            function validateClientUrlRedirect(classEl, url)
            {
                if(!validURL(url)) {
                    enableError(classEl, '{{ __('create_client_app.error_url_redirect_not_url') }}');

                    return false;
                }

                disableError(classEl);

                return true;
            }

            function validURL(url) {
                var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;

                if(!regex .test(url)) {
                    return false;
                } else {
                    return true;
                }
            }

            function validateIPaddress(classEl, ip)
            {
                if(ip !='' && !checkIsIp(ip)) {
                    enableError(classEl, '{{ __('create_client_app.error_ip_secure_is_ip') }}');

                    return false;
                }

                disableError(classEl);

                return true;
            }

            function checkIsIp(ip)
            {
                var x = ip.split("."), x1, x2, x3, x4;

                if (x.length == 4) {
                    x1 = parseInt(x[0], 10);
                    x2 = parseInt(x[1], 10);
                    x3 = parseInt(x[2], 10);
                    x4 = parseInt(x[3], 10);

                    if (isNaN(x1) || isNaN(x2) || isNaN(x3) || isNaN(x4)) {
                        return false;
                    }

                    if ((x1 >= 0 && x1 <= 255) && (x2 >= 0 && x2 <= 255) && (x3 >= 0 && x3 <= 255) && (x4 >= 0 && x4 <= 255)) {
                        return true;
                    }
                }
                return false;
            }
        })
    </script>
@endsection

@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="col-md-6">
            @if (session('error'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
             @endif
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('create_client_app.title_create_client_app') }}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ route('create_client_app') }}" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group client-name @if ($errors->has('client_name')) has-error @endif">
                            <label for="exampleInputEmail1">{{ __('create_client_app.client_name') }}</label>
                            <input type="text" name="client_name" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block"> @if ($errors->has('client_name')) {{ $errors->first('client_name') }} @endif</span>
                        </div>
                        <div class="form-group url-redirect @if ($errors->has('url_redirect')) has-error @endif">
                            <label for="exampleInputPassword1">{{ __('create_client_app.client_call_back') }}</label>
                            <input type="text" name="url_redirect" class="form-control" id="exampleInputPassword1" placeholder="ex: http://example.com/callback">
                            <span class="help-block">@if ($errors->has('url_redirect')) {{ $errors->first('url_redirect') }} @endif</span>
                        </div>
                        <div class="form-group ip-secure @if ($errors->has('ip_secure')) has-error @endif" >
                            <label for="exampleInputPassword1">{{ __('create_client_app.ip_secure') }}</label>
                            <input type="text" name="ip_secure" class="form-control" id="exampleInputPassword1" placeholder="">
                            <span class="help-block"> @if ($errors->has('ip_secure')) {{ $errors->first('ip_secure') }} @endif</span>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-change-password">{{ __('create_client_app.btn_create_new_client_app') }}</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </section><!-- /.content -->
@endsection