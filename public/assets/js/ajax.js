$(document).ready(function() {
    $('#login-form').submit(function (event) {
        var payload = {
            username: $('input[name=username]').val(),
            password: $('input[name=password]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/login',
            contentType: 'application/json;charset=utf-8',
            data: JSON.stringify(payload), // our data object
            dataType: 'json' // what type of data do we expect back from the server
        })
            .done(function (data) {
                console.log(data);
            })
            .fail(function (error) {
                console.log(error);
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });
});
$(document).ready(function() {
    $('#register-form').submit(function(event) {
        var payload = {
            name: $('input[name=name]').val(),
            username: $('input[name=username]').val(),
            email: $('input[name=email]').val(),
            birthdate: $('input[name=birthdate]').val(),
            phone_number: $('input[name=phone_number]').val(),
            password: $('input[name=password]').val(),
            confirm_password: $('input[name=confirm_password]').val()
        };

        let stop = 0;
        let errors = validateRegister(payload);

            if (errors["name"]) {
                $("#nameError").text(errors["name"]);
                stop = 1;
            }
            else $("#nameError").text("");

            if (errors["nameFormat"]){
                $("#nameErrorFormat").text(errors["nameFormat"]);
                stop = 1;
            }
            else $("#nameErrorFormat").text("");

            if (errors['username']){
                $("#usernameError").text(errors['username']);
                stop = 1;
            }
            else $("#usernameError").text("");

            if (errors['usernameFormat']) {
                $("#usernameErrorFormat").text(errors['usernameFormat']);
                stop = 1;
            }
            else $("#usernameErrorFormat").text("");

            if (errors['password']){
                $("#passwordError").text(errors['password']);
                stop = 1;
            }
            else $("#passwordError").text("");

            if (errors['email']) {
                $("#emailErrorFormat").text(errors['email']);
                stop = 1;
            }
            else $("#emailErrorFormat").text("");

            if (errors['phone_number']) {
                $("#phoneNumberError").text(errors['phone_number']);
                stop = 1;
            }
            else $("#phoneNumberError").text("");

            if (errors["phone_number_length"]) {
                $("#phoneNumberErrorLength").text(errors["phone_number_length"]);
                stop = 1;
            }
            else $("#phoneNumberErrorLength").text("");

            if (errors["confirm_password"]) {
                $("#confirmPasswordError").text(errors["confirm_password"]);
                stop = 1;
            }
            else $("#confirmPasswordError").text("");

            if (stop == 1) {
                event.preventDefault();
            }


    });
});

function validateRegister(payload) {
    var errors = [];

    if (!payload["name"]){
        errors["name"] = 'The name cannot be empty';
    }

    if (!payload["name"].match(/^[a-zA-Z ]+$/) ){
        errors["nameFormat"] = 'The name must contain only alphanumerical characters';
    }

    if (!payload['username'] || payload['username'].length > 20) {
        errors['username'] = 'The username must be between 1 and 20 characters';
    }

    if (!payload['username'].match(/^[a-zA-Z ]+$/) ){
        errors['usernameFormat'] = 'The username must contain only alphanumerical characters';
    }

    if (!payload['password'] || payload['password'].length < 6) {
        errors['password'] = 'The password must contain at least 6 characters';
    }

    if ((!/^\w+([\.-]?w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(payload['email']))) {
        errors['email'] = 'The email is not valid';
    }

    if(!payload['phone_number']){
        errors['phone_number'] = 'Phone number cannot be empty';
    }

    if(!payload['phone_number'].match(/^\d{3}\s\d{3}\s\d{3}/)){
        errors["phone_number_length"] = "Phone number must be in format XXX XXX XXX";
        console.log("hellooooo");
    }

    if (payload["confirm_password"] !== payload["password"]) {
        errors["confirm_password"] = "Password confirmation doesn't match password";
    }


    return errors;
}
