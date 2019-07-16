$(document).ready(function() {
    $('#login-form').submit(function(event) {
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
            .done(function(data) {
                console.log(data);
            })
            .fail(function(error) {
                console.log(error);
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });


    $('#register-form').submit(function(event) {
        var payload = {
            name: $('input[name=name]').val(),
            username: $('input[name=username]').val(),
            email: $('input[name=email]').val(),
            birthdate: $('input[name=birthdate]').val(),
            phone_number: $('input[name=phone_number]').val(),
            password: $('input[name=password]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/register',
            contentType: 'application/json;charset=utf-8',
            data: JSON.stringify(payload), // our data object
            dataType: 'json' // what type of data do we expect back from the server
        })
        let errors = validateRegister(payload);
        if(Object.keys(errors).length > 0){
            event.preventDefault();
            if (errors['name']) $('#name_error').text(errors['name']);
            else $('#name_error').text("");
        }
    });
});

function validateRegister(payload) {
   /* var errors = [];

    if (empty(input[name=name])){
        $errors['name'] = 'The name cannot be empty';
    }

    if (!ctype_alnum($data['name'] )){
        $errors['nameFormat'] = sprintf('The name must contain only alphanumerical characters');
    }

    if (empty($data['username']) || strlen($data['username']) > 20) {
        $errors['username'] = 'The username must be between 1 and 20 characters';
    }

    if (!ctype_alnum($data['username'] )){
        $errors['usernameFormat'] = sprintf('The username must contain only alphanumerical characters');
    }

    if ($unique != -1) {
        $errors['usernameCaught'] = 'The username is already in use';
    }

    if (empty($data['password']) || strlen($data['password']) < 6) {
        $errors['password'] = 'The password must contain at least 6 characters';
    }

    if (false === filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = sprintf('The email %s is not valid', $data['email']);
    }

    if (strcmp($data['confirm_password'], $data['password'])) {
        $errors['repPassword'] = "Password confirmation doesn't match password";
    }


    return $errors;*/
}
