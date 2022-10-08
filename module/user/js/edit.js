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
            var password1 = $('#password1').val().trim();
            var password2 = $('#password2').val().trim();
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
    $('#visions').change();
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

var groups = $('#groups').val();
$(document).on('change', '#groups', function(){groups = $('#groups').val()});

$("#visions").change(function()
{
    visions = $(this).val();
    $.post(createLink('user', 'ajaxGetGroup', "visions=" + visions + '&i=' + 0 + '&selected=' + groups), function(data)
    {
        $('#groups').replaceWith(data);
        $('#groups' + '_chosen').remove();
        $('#group').attr('id', 'groups').attr('name', 'groups[]');
        $('#groups').chosen();
    });
});
