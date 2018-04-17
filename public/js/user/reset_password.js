$(function () {
    $('.btn-change-password').click(function(){
        var new_password         = $('.new-password input').val();
        var confirm_new_password = $('.confirm-new-password input').val();

        if (new_password == '') {
            enableError('new-password', 'Please fill out this field');

            return false;
        } else {
            disableError('new-password')
        }

        if (confirm_new_password != new_password) {
            enableError('confirm-new-password', 'Confirm password is not matching.');

            return false;
        } else {
            disableError('confirm-new-password')
        }

        if (!validatePassword('new-password', $('.new-password input').val())) {

            return false;
        }

        if (!validatePassword('confirm-new-password',  $('.confirm-new-password input').val())) {
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
        if(password.length < 8) {
            enableError(classEl, 'Password needs > 8 letters');

            return false;
        } else if (password.length > 50) {
            enableError(classEl, 'The length <= 50 characters');

            return false;
        }

        disableError(classEl);

        return true;
    }
})