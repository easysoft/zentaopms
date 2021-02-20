$(function()
{
    var password1Encrypted = false
    var password2Encrypted = false
    $('#password1').change(function(){password1Encrypted = false});
    $('#password2').change(function(){password2Encrypted = false});
    $('#submit').click(function()
    {
        var password1 = $('#password1').val();
        var password2 = $('#password2').val();
        var passwordStrength = computePasswordStrength(password1);

        if($("form input[name=passwordStrength]").length == 0) $('#submit').after("<input type='hidden' name='passwordStrength' value='0' />");
        $("form input[name=passwordStrength]").val(passwordStrength);

        var rand      = $('input#verifyRand').val();
        if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
        if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
        password1Encrypted = true;
        password2Encrypted = true;
    })

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

    changeType(type);
});

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
        $('#company').closest('td').addClass('hide');
        $('#groups').closest('td').next('th').addClass('hide');
        $('#dept, #commiter').closest('tr').removeClass('hide');
    }
    else
    {
        $('#company').closest('td').removeClass('hide');
        $('#groups').closest('td').next('th').removeClass('hide');
        $('#dept, #commiter').closest('tr').addClass('hide');
    }
}
