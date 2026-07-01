$(document).ready(function () {
    $('#productsform').bootstrapValidator({
        fields: {
            product_code: {
                validators: {
                    notEmpty: {
                        message: 'The id is required and can\'t be empty'
                    },
                    stringLength: {
                        max: 50,
                        message: 'It must be less than 50 characters'
                    }
                }
            },
            product_name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required and can\'t be empty'
                    },
                    stringLength: {
                        max: 50,
                        message: 'It must be less than 50 characters'
                    }
                }
            },
            status: {
                validators: {
                    notEmpty: {
                        message: 'The status is required and can\'t be empty'
                    }
                }
            },
            product_details: {
                validators: {
                    notEmpty: {
                        message: 'The details are required and can\'t be empty'
                    },
                    stringLength: {
                        max: 50,
                        message: 'It must be less than 50 characters'
                    }
                }
            },
            amount: {
                validators: {
                    notEmpty: {
                        message: 'The amount is required and can\'t be empty'
                    },

                    regexp: {
                        regexp: /^[0-9\.]+$/,
                        message: 'Enter valid amount'
                    }
                }
            }

        }
    });
});

