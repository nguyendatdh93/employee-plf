<script>
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
        if(password.length < {{ \App\Models\User::MIN_LIMIT_PASSWORD }}) {
            enableError(classEl, "{{ __('change_password.lenght_8') }}");

            return false;
        } else if (password.length > {{ \App\Models\User::MAX_LIMIT_PASSWORD }}) {
            enableError(classEl, "{{ __('change_password.lenght_50') }}");

            return false;
        }

        disableError(classEl);

        return true;
    }
</script>