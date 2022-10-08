$(function()
{
    changeVision();

    $("input[name='new[]']").change(function()
    {
        if($(this).prop('checked'))
        {
            $('#company').replaceWith("<input name='company' id='company' class='form-control'/>");
            $('#company_chosen').remove();
        }
        else
        {
            var link = createLink('company', 'ajaxGetOutsideCompany');
            $.post(link, function(data)
            {
                $('#company').replaceWith(data);
                $('#company').chosen();
            })
        }
    })
})

$("input[name='visions[]']").change(function()
{
    changeVision();
});

function changeVision()
{
    var visions = [];
    $('input[name="visions[]"]:checked').each(function()
    {
        visions.push($(this).val());
    });

    var link = createLink('user', 'ajaxGetGroup', 'visions=' + visions + '&i=0&selected=' + $('#group').val());
    $.post(link, function(data)
    {
        $('#group').replaceWith(data);
        $('#group_chosen').remove();
        $('#group').chosen();
    })
}

/**
 * Change group when change role.
 *
 * @param  role $role
 * @access public
 * @return void
 */
function changeGroup(role)
{
    if(role && roleGroup[role])
    {
        $('#group').val(roleGroup[role]);
    }
    else
    {
        $('#group').val('');
    }
    $('#group').trigger("chosen:updated");
}

/**
 * Show or hide companies based on user type.
 *
 * @param  type $type
 * @access public
 * @return void
 */
function changeType(type)
{
    if(type == 'inside')
    {
        $('#companyBox').addClass('hide');
        $('#dept, #join, #commiter').closest('tr').removeClass('hide');
    }
    else
    {
        $('#companyBox').removeClass('hide');
        $('#dept, #join, #commiter').closest('tr').addClass('hide');
    }
}

$(function()
{
    var password1Encrypted = false
    var password2Encrypted = false
    $('#password1').change(function(){password1Encrypted = false});
    $('#password2').change(function(){password2Encrypted = false});

    var passwordStrength = 0;
    $('#submit').click(function()
    {
        if(!password1Encrypted || !password2Encrypted)
        {
            var password1 = $('#password1').val();
            var password2 = $('#password2').val();
            if(!password1Encrypted)
            {
                passwordStrength = computePasswordStrength(password1);
                $("#passwordLength").val(password1.length);
            }

            if($("form input[name=passwordStrength]").length == 0) $('#submit').after("<input type='hidden' name='passwordStrength' value='0' />");
            $("form input[name=passwordStrength]").val(passwordStrength);

            var rand      = $('input#verifyRand').val();
            if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
            if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
            password1Encrypted = true;
            password2Encrypted = true;
        }
    })
});
