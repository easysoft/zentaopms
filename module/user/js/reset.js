$(document).ready(function()
{
    $('form #submit').click(function()
    {
        var password         = $('#password1').val().trim();
        var passwordStrength = computePasswordStrength(password);

        $('form').prepend("<input type='hidden' name='passwordStrength' value=" + passwordStrength + "/>");
    });
});
