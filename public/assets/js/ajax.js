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
                $("#name_error").text(errors["name"]);
                stop = 1;
            }
            else $("#name_error").text("");
            if (errors["nameFormat"]){
                $("#nameErrorFormat").text(errors["nameFormat"]);
                stop = 1;
            }
            else $("#nameErrorFormat").text("");
            if (errors['username']){
                $('#username_error').text(errors['username']);
                stop = 1;
            }
            else $('#username_error').text("");
            if (errors['usernameFormat']) {
                $('#name_error_format').text(errors['usernameFormat']);
                stop = 1;
            }
            else $('#name_error_format').text("");
            if (errors['password']){
                $('#password_error').text(errors['password']);
                stop = 1;
            }
            else $('#password_error').text("");
            if (errors['email']) {
                $('#email_error_format').text(errors['email']);
                stop = 1;
            }
            else $('#email_error_format').text("");
            if (errors['phone_number']) {
                $('#phone_number_error').text(errors['phone_number']);
                stop = 1;
            }
            else $('#phone_number_error').text("");
            if (errors['phone_number_length']) {
                $('#phone_number_error_length').text(errors['phone_number_length']);
                stop = 1;
            }
            else $('#phone_number_error_length').text("");

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

    if(payload['phone_number'].match(/^\d{10}$/)){
        errors['phone_number_length'] = 'Phone number must be in format XXX XXX XXX';
    }

    if (payload["confirm_password"] !== payload["password"]) {
        errors["confirm_password"] = "Password confirmation doesn't match password";
    }


    return errors;
}

$(document).ready(function() {
    $('#add-form').submit(function(event) {
        var payload = {
            title: $('#title').val(),
            description: $('input[name=description]').val(),
            price: $('input[name=price]').val(),
            category: $('#category').val()
        };

        let stop = 0;
        let errors = validateAdd(payload);

        if (errors['title']) {
            $("#titleError").text(errors['title']);
            stop = 1;
        }
        else $("#titleError").text("");
        if (errors["description"]){
            $("#description").text(errors["description"]);
            stop = 1;
        }
        else $("#description").text("");
        if (errors['price']){
            $('#price').text(errors['price']);
            stop = 1;
        }
        else $('#price').text("");
        if (errors['category']){
            $('#categoryError').text(errors['category']);
            stop = 1;
        }
        else $('#categoryError').text("");
        if (stop == 1) {
            event.preventDefault();
        }
    });
});

function validateAdd(payload) {
    var errors = [];

    var categories = ['Computers and electronic', 'Cars', 'Sports', 'Games', 'Fashion', 'Home', 'Other'];

    if (!payload['title']){
        errors['title'] = 'The title cannot be empty';
    }

    if (!payload['description'] || payload['description'].length < 20) {
        errors['description'] = 'The description must have a minimum of 20 characters';
    }

    if (!payload['price'].match(/^\d+(\.\d{1,2})?$/) ){
        errors['price'] = 'The price must have the correct format';
    }

    if (!payload['category'] || !categories.includes(payload['category'])) {
        errors['category'] = 'The category must be one of the list';
    }


    return errors;
}
