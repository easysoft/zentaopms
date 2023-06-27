$(function()
{
    password1Encrypted = false;
    password2Encrypted = false;
    changeType();
    updateGroup();
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
        $('#company').closest('.form-group').addClass('hidden');
        $('#dept, #commiter').closest('.form-row').removeClass('hidden');
    }
    else
    {
        $('#company').closest('.form-group').removeClass('hidden');
        $('#dept, #commiter').closest('.form-row').addClass('hidden');
    }
}

/**
 * Update groups when visions change.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function updateGroup(event)
{
    let visions      = '';
    let group        = $('#group').val();
    let $visionGroup = event != undefined ? $(event.target).closest('.form-group') : $('input[name="visions"]').closest('.form-group');
    $.each($visionGroup.find('input[type="checkbox"]:checked'), function(index)
    {
        if(index > 0) visions += ',';
        visions += $(this).val();
    });
    $.post($.createLink('user', 'ajaxGetGroup', "visions=" + visions + '&i=' + 0 + '&selected=' + group), function(data)
    {
        $('#group').replaceWith(data);
    });
}

function toggleNew(event)
{
    const $company    = $('#company');
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
