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

                if (!validateClientUrlRedirect('url-redirect',  url_redirect)) {
                    return false;
                }

                if (!validateIPaddress('ip-secure',  ip_secure)) {
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