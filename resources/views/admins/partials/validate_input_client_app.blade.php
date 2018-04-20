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
        })
    </script>
@endsection