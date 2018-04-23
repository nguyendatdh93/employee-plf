@section('Datatable')
    <script>
        $(function () {
            $('.btn-change-password').click(function(){
                var client_name  = $('.client-name input').val();
                var url_redirect = $('.url-redirect input').val();
                var ip_secure    = $('.ip-secure input').val();

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