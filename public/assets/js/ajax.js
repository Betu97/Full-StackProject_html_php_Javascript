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
            .done(function(data) {
                console.log(data);
            })
            .fail(function(error) {
                console.log(error);
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });
});
