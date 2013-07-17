$(function()
{
    if(result == 'success')
    {
        $.get(createLink('upgrade', 'ajaxCheckExtension'), function(data)
        {
            $('#checkExtension').html(data);
            $('#tohome').html(tohome);
        });
    }
});
