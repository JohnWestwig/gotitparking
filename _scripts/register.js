$(document).ready(function () {
    $('#registration').bootstrapValidator({
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'First name is required'
                    }
                }
            },
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'First name is required'
                    }
                }
            },
            email: {
                validators: {
                     emailAddress: {
                        message: 'Valid e-mail address is required'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Password is required'
                    }
                }
            },
            confirm_password: {
                validators: {
                    notEmpty: {
                        message: 'Password confirmation is required'
                    },
                    identical: {
                        field: 'password',
                        message: 'Passwords do not match'
                    }
                }
            },

        }
    });
});
