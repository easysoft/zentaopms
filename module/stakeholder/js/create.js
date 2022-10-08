$(function()
{
    $('input[name*=from]').change(function()
    {
        $('#userLabel').remove();

        if($(this).val() == 'team')
        {
            $('.user-info').addClass('hidden');
            $('#user').closest('tr').find('.input-group-addon').addClass('hidden');
            var link = createLink('stakeholder', 'ajaxGetMembers', 'user=&program=' + programID + '&projectID=' + projectID);
            $.post(link, function(data)
            {
                $('#user').replaceWith(data);
                $('#user_chosen').remove();
                $('#user').chosen();
            })
        }

        if($(this).val() == 'company')
        {
            $('.user-info').addClass('hidden');
            $('#user').closest('tr').find('.input-group-addon').addClass('hidden');
            var link = createLink('stakeholder', 'ajaxGetCompanyUser', 'user=&programID=' + programID + '&projectID=' + projectID);
            $.post(link, function(data)
            {
                $('#user').replaceWith(data);
                $('#user_chosen').remove();
                $('#user').chosen();
            })
        }

        if($(this).val() == 'outside')
        {
            $('#user').closest('tr').find('.input-group-addon').removeClass('hidden');
            if($('input[name*=newUser]').prop('checked')) $('.user-info').removeClass('hidden');
            var objectID = programID ? programID : projectID;
            var link = createLink('stakeholder', 'ajaxGetOutsideUser', 'objectID=' + objectID);
            $.post(link, function(data)
            {
                $('#user').replaceWith(data);
                $('#user_chosen').remove();
                $('#user').chosen();
            })
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

    $('input[name*=newUser]').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#user').attr('disabled', true).trigger("chosen:updated");
            $('.user-info').removeClass('hidden');
        }
        else
        {
            $('#user').attr('disabled', false).trigger("chosen:updated");
            $('.user-info').addClass('hidden');
        }
    })
})

function changeUser(value)
{
    if(value)
    {
        $('.user-info').addClass('hidden');
    }
    else
    {
        $('.user-info').removeClass('hidden');
    }
}
