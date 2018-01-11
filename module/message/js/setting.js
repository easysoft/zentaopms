$(function()
{
    $('.objectType').click(function()
    {
        if($(this).prop('checked'))
        {
            $(this).closest('tr').find(':checkbox').prop('checked', true);
        }
        else
        {
            $(this).closest('tr').find(':checkbox').prop('checked', false);
        }
    });

    $('.messageType').click(function()
    {
        var index = $(this).closest('th').index();
        if($(this).prop('checked'))
        {
            $(this).closest('table').find('tbody').find('tr').each(function()
            {
                $(this).find('td').eq(index).find(':checkbox').prop('checked', true);
            })
        }
        else
        {
            $(this).closest('table').find('tbody').find('tr').each(function()
            {
                $(this).find('td').eq(index).find(':checkbox').prop('checked', false);
            })
        }
    });
});
