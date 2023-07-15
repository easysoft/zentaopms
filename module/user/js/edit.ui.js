$(function()
{
    password1Encrypted = false;
    password2Encrypted = false;
    changeType();
});

/**
 * When password1 change.
 *
 * @access public
 * @return void
 */
function password1Change()
{
    password1Encrypted = false;
}

/**
 * When password2 change.
 *
 * @access public
 * @return void
 */
function password2Change()
{
    password2Encrypted = false;
}

function computePassword()
{
    if(!password1Encrypted || !password2Encrypted)
    {
        var password1 = $('#password1').val().trim();
        var password2 = $('#password2').val().trim();
        if(!password1Encrypted)
        {
            $("#passwordStrength").val(computePasswordStrength(password1));
            $("#passwordLength").val(password1.length);
        }


        var rand = $('input#verifyRand').val();
        if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
        if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
        password1Encrypted = true;
        password2Encrypted = true;
    }
}

/**
 * Show or hide companies based on user type.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function changeType(event)
{
    let $typeGroup = event != undefined ? $(event.target).closest('.form-group') : $('input[name="type"]').closest('.form-group');
    let type       = $typeGroup.find('input[type="radio"]:checked').val();
    if(type == 'inside')
    {
        $('[name="company"]').closest('.form-group').addClass('hidden');
        $('[name="dept"]').closest('.form-row').removeClass('hidden');
        $('#commiter').closest('.form-row').removeClass('hidden');
    }
    else
    {
        $('[name="company"]').closest('.form-group').removeClass('hidden');
        $('[name="dept"]').closest('.form-row').addClass('hidden');
        $('#commiter').closest('.form-row').addClass('hidden');
    }
}

function toggleNew(event)
{
    const $company    = $('[name="company"]').closest('.picker-box');
    const $newCompany = $('#newCompany');
    if($(event.target).prop('checked'))
    {
        $company.addClass('hidden');
        $newCompany.removeClass('hidden');
    }
    else
    {
        $company.removeClass('hidden');
        $newCompany.addClass('hidden');
    }
}
