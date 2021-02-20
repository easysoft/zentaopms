$(function()
{
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
    $('#submit').click(function()
    {
        var password1        = $('#password1').val();
        var password2        = $('#password2').val();
        var passwordStrength = computePasswordStrength(password1);

        if($("form input[name=passwordStrength]").length == 0) $('#submit').after("<input type='hidden' name='passwordStrength' value='0' />");
        $("form input[name=passwordStrength]").val(passwordStrength);

        var rand      = $('input#verifyRand').val();
        if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
        if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
        password1Encrypted = true;
        password2Encrypted = true;
    })
});
