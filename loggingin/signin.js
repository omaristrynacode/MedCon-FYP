$(document).ready(function() {
    // Get the PHP error message from the hidden div
    var phpErrorMessage = $('#php-error').data('message');
console.log(phpErrorMessage);
    // If there's a message, display it in the correct spot
    if (phpErrorMessage) {
        $('.error').text(phpErrorMessage).show();
        $('.error').css("display", "block"); // Ensure it's visible
    }
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('error_message');
    console.log(message)

    // If a message is found, display it
    if (message) {
       
        $('#login').animate({height: "toggle", opacity: "toggle"}, "slow");
        $('#regform').animate({height: "toggle", opacity: "toggle"}, "slow");
        $('.error_reg').text(decodeURIComponent(message)).show();
        $('.error_reg').css("display", "block");
    }
    function clearUrlParams() {
        const url = window.location.origin + window.location.pathname;
        window.history.replaceState(null, null, url);
    }


    // Toggle between login and sign-up forms
    $('.message a').click(function() {
        $('#login').animate({height: "toggle", opacity: "toggle"}, "slow");
        $('#regform').animate({height: "toggle", opacity: "toggle"}, "slow");
        clearUrlParams();
        $('.error').css("display", "none");
        $('.error_reg').css("display", "none");
    });

    $("button").click(function(e) {
        e.preventDefault(); // Prevent form submission on button click
        if ($('.login-form').is(':visible')) {
            if ($("#name_log").val() === "" || $("#pass_log").val() === "") {
                $(".error").text("Please fill out all login fields.").show();
        $('.error').css("display", "block"); // Ensure it's visible

            } else {
                // Submit the login form
                $('form.login-form').submit();
            }
        }

        if ($('.register-form').is(':visible')) {
            if ($("#first_name_reg").val() === "" || $("#last_name_reg").val() === "" || $("#pass_reg").val() === "" || !IsEmail($("#email_reg").val())) {
                $(".error_reg").text("Please fill out all fields and provide a valid email.").show();
            }
             else {
                $('form.register-form').submit();
            }
        }
    });

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
});
