$(function()
{
    $('input[name*=from]').change(function()
    {
        var value = $('input[name*=from]:checked').val();
        if(value == 'team')
        {
            $('.user-info').addClass('hidden');
            var link = createLink('stakeholder', 'ajaxGetMembers', 'user=' + user); 
            $.post(link, function(data)
            {
                $('#user').replaceWith(data);        
                $('#user_chosen').remove();
                $('#user').chosen();        
            })
        }

        if(value == 'company')
        {
            $('.user-info').addClass('hidden');
            var link = createLink('stakeholder', 'ajaxGetCompanyUser', 'user=' + user); 
            $.post(link, function(data)
            {
                $('#user').replaceWith(data);        
                $('#user_chosen').remove();
                $('#user').chosen();        
            })
        }

        if(value == 'outside')
        {
            $('.user-info').removeClass('hidden');
            var link = createLink('stakeholder', 'ajaxGetOutsideUser'); 
            $.post(link, function(data)
            {
                $('#user').replaceWith(data);        
                $('#user').val(user);        
                $('#user_chosen').remove();
                $('#user').trigger('chosen:updated');        
                $('#user').chosen();        
            })
        }
    })        

    $('input[name*=from]').change();
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
