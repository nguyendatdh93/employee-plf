$(function () {
    $('.btn-change-password').click(function(){
        var current_password     = $('.current-password input').val();
        var new_password         = $('.new-password input').val();
        var confirm_new_password = $('.confirm-new-password input').val();

        if (current_password == '') {
            enableError('current-password', 'Please fill out this field');

            return false;
        } else {
            disableError('current-password')
        }

        if (new_password == '') {
            enableError('new-password', 'Please fill out this field');

            return false;
        }else if (new_password.length < 8) {
            enableError('new-password', 'The length >= 8 characters');

            return false;
        }else if (new_password.length > 50) {
            enableError('new-password', 'The length <= 50 characters');

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
        // var lowerCaseLetters = /[a-z]/g;
        // if(!password.match(lowerCaseLetters)) {
        //     enableError(classEl, 'Password needs a lowercase letter');
        //
        //     return false;
        // }
        //
        // // Validate capital letters
        // var upperCaseLetters = /[A-Z]/g;
        // if(!password.match(upperCaseLetters)) {
        //     enableError(classEl, 'Password needs a uppercase letter');
        //
        //     return false;
        // }

        // // Validate numbers
        // var numbers = /[0-9]/g;
        // if(!password.match(numbers)) {
        //     enableError(classEl, 'Password needs a number letter');
        //
        //     return false;
        // }

        // Validate length
        if(password.length < 8) {
            enableError(classEl, 'Password needs > 8 letters');

            return false;
        }

        disableError(classEl);

        return true;
    }
})