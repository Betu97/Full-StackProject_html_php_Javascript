$(document).ready(function() {
    $('#loginForm').submit(function(event) {
        var payload = {
            email: $('input[name=email]').val(),
            password: $('input[name=password]').val()
        };

        let stop = 0;
        let errors = validateLogin(payload);

        if (errors['email']) {
            $("#emailErrorFormat").text(errors['email']);
            stop = 1;
        }
        else $("#emailErrorFormat").text("");

        if (errors['password']){
            $("#passwordError").text(errors['password']);
            stop = 1;
        }
        else $("#passwordError").text("");

        if (stop == 1) {
            event.preventDefault();
        }


    });
});

function validateLogin(payload) {
    var errors = [];


    if ((!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(payload['email']))) {
        errors['email'] = 'The email is not valid';
    }

    if (!payload['password'] || payload['password'].length < 6) {
        errors['password'] = 'The password must contain at least 6 characters';
    }

    return errors;
}
$(document).ready(function() {
    $('#registerForm').submit(function(event) {
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

            if (errors["birthdate"]) {
                $("#birthdateError").text(errors["birthdate"]);
                stop = 1;
            }
            else $("#birthdateError").text("");

            if (stop == 1) {
                event.preventDefault();
            }


    });
});

function validateRegister(payload) {
    var errors = [];

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    var birthdate = new Date(payload["birthdate"]);
    var d = String(birthdate.getDate()).padStart(2, '0');
    var m = String(birthdate.getMonth() + 1).padStart(2, '0');
    var y = birthdate.getFullYear();


    if (!payload["name"]){
        errors["name"] = 'The name cannot be empty';
    }

    if (!payload["name"].match(/^[a-zA-Z\d]+$/) ){
        errors["nameFormat"] = 'The name must contain only alphanumerical characters';
    }

    if (y > yyyy || (y == yyyy && m > mm) || (y == yyyy && m == mm && d > dd) ){
        errors["birthdate"] = 'The date is not correct';
    }

    if (!payload['username'] || payload['username'].length > 20) {
        errors['username'] = 'The username must be between 1 and 20 characters';
    }

    if (!payload['username'].match(/^[a-zA-Z\d]+$/) ){
        errors['usernameFormat'] = 'The username must contain only alphanumerical characters';
    }

    if (!payload['password'] || payload['password'].length < 6) {
        errors['password'] = 'The password must contain at least 6 characters';
    }

    if ((!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(payload['email']))) {
        errors['email'] = 'The email is not valid';
    }

    if(!payload['phone_number']){
        errors['phone_number'] = 'Phone number cannot be empty';
    }

    if(!payload['phone_number'].match(/^\d{3}\s\d{3}\s\d{3}/)){
        errors["phone_number_length"] = "Phone number must be in format XXX XXX XXX";
    }

    if (payload["confirm_password"] !== payload["password"]) {
        errors["confirm_password"] = "Password confirmation doesn't match password";
    }


    return errors;
}

$(document).ready(function() {
    $('#addForm').submit(function(event) {
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

$(document).ready(function() {
    $('#profileForm').submit(function(event) {
        var payload = {
            name: $('input[name=name]').val(),
            email: $('input[name=email]').val(),
            birthdate: $('input[name=birthdate]').val(),
            phone_number: $('input[name=phone_number]').val(),
            password: $('#password').val()
        };
        console.log(payload['password']);

        let stop = 0;
        let errors = validateProfile(payload);

        if (errors["nameFormat"]){
            $("#nameErrorFormat").text(errors["nameFormat"]);
            stop = 1;
        }
        else $("#nameErrorFormat").text("");

        if (errors['email']) {
            $("#emailErrorFormat").text(errors['email']);
            stop = 1;
        }
        else $("#emailErrorFormat").text("");

        if (errors["phone_number_length"]) {
            $("#phoneNumberErrorLength").text(errors["phone_number_length"]);
            stop = 1;
        }
        else $("#phoneNumberErrorLength").text("");

        if (errors["birthdate"]) {
            $("#birthdateError").text(errors["birthdate"]);
            stop = 1;
        }
        else $("#birthdateError").text("");

        if (errors['password']){
            $("#passwordError").text(errors['password']);
            stop = 1;
        }
        else $("#passwordError").text("");

        if (stop == 1) {
            event.preventDefault();
        }

    });
});

function validateProfile(payload) {
    var errors = [];

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    var birthdate = new Date(payload["birthdate"]);
    var d = String(birthdate.getDate()).padStart(2, '0');
    var m = String(birthdate.getMonth() + 1).padStart(2, '0');
    var y = birthdate.getFullYear();

    if (y > yyyy || (y == yyyy && m > mm) || (y == yyyy && m == mm && d > dd) ){
        errors["birthdate"] = 'The date is not correct';
    }

    if (!payload["name"].match(/^[a-zA-Z\d]+$/) ){
        errors["nameFormat"] = 'The name must contain only alphanumerical characters';
    }

    if ((!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(payload['email']))) {
        errors['email'] = 'The email is not valid';
    }

    if (payload['password'].length < 6 && payload['password'].length > 0) {
        errors['password'] = 'The password must contain at least 6 characters';
    }

    if(!payload['phone_number'].match(/^\d{3}\s\d{3}\s\d{3}/)){
        errors["phone_number_length"] = "Phone number must be in format XXX XXX XXX";
    }

    return errors;
}

$(document).ready(function() {
    $('#overviewForm').submit(function(event) {
        var payload = {
            title: $('#title').val(),
            description: $('input[name=description]').val(),
            price: $('input[name=price]').val(),
            category: $('#category').val()
        };

        let stop = 0;
        let errors = validateOverview(payload);

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

function validateOverview(payload) {
    var errors = [];

    var categories = ['Computers and electronic', 'Cars', 'Sports', 'Games', 'Fashion', 'Home', 'Other'];

    console.log(payload['category']);

    if (payload['description'].length < 20) {
        errors['description'] = 'The description must have a minimum of 20 characters';
    }

    if (!payload['price'].match(/^\d+(\.\d{1,2})?$/) ){
        errors['price'] = 'The price must have the correct format';
    }

    if (!categories.includes(payload['category']) && !empty(payload['category'])) {
        errors['category'] = 'The category must be one of the list';
    }


    return errors;
}
