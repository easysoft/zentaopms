$(function()
{
    password1Encrypted = false;
    password2Encrypted = false;
    passwordStrength   = 0;
})

function changePassword(event)
{
    if($(event.target).attr('id') == 'password1')
    {
        password1Encrypted = false;
    }
    else
    {
        password2Encrypted = false;
    }
}

function changeType(event)
{
    const type = $(event.target).val();
    if(type == 'inside')
    {
        $('#companyBox').addClass('hidden');
        $('#dept, #join, #commiter').closest('.form-row').removeClass('hidden');
    }
    else
    {
        $('#companyBox').removeClass('hidden');
        $('#dept, #join, #commiter').closest('.form-row').addClass('hidden');
    }
}

function changeAddCompany(event)
{
    const checked = $(event.target).prop('checked');
    if(checked)
    {
        $('#company').replaceWith("<input name='company' id='company' class='form-control'/>");
    }
    else
    {
        const link = $.createLink('company', 'ajaxGetOutsideCompany');
        $.post(link, function(data)
        {
            $('#company').replaceWith(data);
        })
    }
}

function changeVision(event)
{
    var visions = [];
    $('input[name="visions[]"]:checked').each(function()
    {
        visions.push($(this).val());
    });

    const group = $('#group').val();
    const link  = $.createLink('user', 'ajaxGetGroup', 'visions=' + visions + '&i=0&selected=' + group);
    $.post(link, function(data)
    {
        $('#group').replaceWith(data);
    })
}

function clickSubmit()
{
    if(!password1Encrypted || !password2Encrypted)
    {
        const password1 = $('#password1').val();
        const password2 = $('#password2').val();
        if(!password1Encrypted)
        {
            passwordStrength = computePasswordStrength(password1);
            $("#passwordLength").val(password1.length);
        }

        if($("form input[name=passwordStrength]").length == 0) $('#submit').after("<input type='hidden' name='passwordStrength' value='0' />");
        $("form input[name=passwordStrength]").val(passwordStrength);

        const rand = $('input#verifyRand').val();
        if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
        if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
        password1Encrypted = true;
        password2Encrypted = true;
    }
}

function computePasswordStrength(password)
{
    if(password.length == 0) return 0;

    var strength = 0;
    var length   = password.length;

    var complexity  = new Array();
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

