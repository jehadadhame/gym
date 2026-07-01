$(document).ready(function () {
    $('#subscriptionsform').bootstrapValidator({
        fields: {
            date: {
                validators: {
                    notEmpty: {
                        message: 'The cheque date is required and can\'t be empty'
                    }
                }
            },
            number: {
                validators: {
                    notEmpty: {
                        message: 'The cheque number is required and can\'t be empty'
                    }
                }
            },
        }
    });
});