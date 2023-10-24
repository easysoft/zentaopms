function checkPassword()
{
    const password  = $(event.target).val();
    const $strength = $(event.target).closest('.input-group').find('.' + strengthClass);
    if(password == '')
    {
        $strength.html('').addClass('hidden');
        return false;
    }

    const passwordStrength = passwordStrengthList[computePasswordStrength(password)];
    $strength.html(passwordStrength).removeClass('hidden');
}

function computePasswordStrength(password)
{
    if(password.length == 0) return 0;

    var strength = 0;
    var length   = password.length;

    var complexity = new Array();
    for(i = 0; i < length; i++)
    {
        letter = password.charAt(i);
        var asc = letter.charCodeAt();
        if(asc >= 48 && asc <= 57)
        {
            complexity[0] = 1;
        }
        else if((asc >= 65 && asc <= 90))
        {
            complexity[1] = 2;
        }
        else if(asc >= 97 && asc <= 122)
        {
            complexity[2] = 4;
        }
        else
        {
            complexity[3] = 8;
        }
    }

    var sumComplexity = 0;
    for(i in complexity) sumComplexity += complexity[i];

    if((sumComplexity == 7 || sumComplexity == 15) && password.length >= 6) strength = 1;
    if(sumComplexity == 15 && password.length >= 10) strength = 2;

    return strength;
}
