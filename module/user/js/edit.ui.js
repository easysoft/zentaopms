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

    $('[name=company]').closest('.form-group').toggleClass('hidden', type == 'inside');
    $('[name=dept]').closest('.form-row').toggleClass('hidden', type != 'inside');
    $('#commiter').closest('.form-row').toggleClass('hidden', type != 'inside');
}

function toggleNew(event)
{
    const $company    = $('[name="company"]').closest('.picker-box');
    const $newCompany = $('#newCompany');

    $company.toggleClass('hidden', $(event.target).prop('checked'));
    $newCompany.toggleClass('hidden', !$(event.target).prop('checked'));
}
