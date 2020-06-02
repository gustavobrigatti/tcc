$(function () {
    $('#form_validation').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        }
    });

    //Advanced Form Validation
    $('#form_advanced_validation').validate({
        rules: {
            'date': {
                customdate: true
            },
            'creditcard': {
                creditcard: true
            },
            'cnpj' : {
                cnpj: true
            },
            'cpf' : {
                cpf: true
            },
            'dateBR' : {
                dateBR: true
            },
            'telefone' : {
                telefone: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        }
    });

    //Custom Validations ===============================================================================
    //Date
    $.validator.addMethod('customdate', function (value, element) {
        return value.match(/^\d\d\d\d?-\d\d?-\d\d$/);
    },
        'Please enter a date in the format YYYY-MM-DD.'
    );

    //Credit card
    $.validator.addMethod('creditcard', function (value, element) {
        return value.match(/^\d\d\d\d?-\d\d\d\d?-\d\d\d\d?-\d\d\d\d$/);
    },
        'Please enter a credit card in the format XXXX-XXXX-XXXX-XXXX.'
    );

    //CNPJ
    $.validator.addMethod('cnpj', function (value, element) {
            return value.match(/[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}\-?[0-9]{2}/);
        },
        'Por favor, forneça um cnpj válido.'
    );

    //CPF
    $.validator.addMethod('cpf', function (value, element) {
            return value.match(/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/);
        },
        'Por favor, forneça um cpf válido.'
    );

    //DATEBR
    $.validator.addMethod('dateBR', function (value, element) {
            return value.match(/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/);
        },
        'Por favor, forneça uma data válida.'
    );

    //Telefone
    $.validator.addMethod('telefone', function (value, element) {
            return value.match(/(\(\d{2}\)\s)(\d{4,5}\-\d{4})/);
        },
        'Por favor, forneça um telefone válido.'
    );
    //==================================================================================================
});
